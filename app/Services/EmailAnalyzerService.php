<?php

namespace App\Services;

use App\Ai\Agents\EmailTriageAgent;
use App\Models\AiAnalysis;
use App\Models\AuditLog;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailAnalyzerService
{
    public function __construct(
        protected EmailTriageAgent $triageAgent = new EmailTriageAgent,
    ) {}

    /**
     * Analyze an incoming email message with Claude and create analysis + draft.
     *
     * @throws Throwable
     */
    public function analyze(EmailMessage $emailMessage): AiAnalysis
    {
        try {
            $prompt = $this->buildPrompt($emailMessage);
            $response = $this->triageAgent->prompt($prompt);

            $data = json_decode((string) $response->text, true);
            if (! is_array($data)) {
                $data = [];
            }

            return DB::transaction(function () use ($emailMessage, $response, $data) {
                $deadline = null;
                if (! empty($data['deadline_detected'])) {
                    try {
                        $deadline = Carbon::parse($data['deadline_detected']);
                    } catch (Throwable) {
                        $deadline = null;
                    }
                }

                $analysis = AiAnalysis::updateOrCreate(
                    ['email_message_id' => $emailMessage->id],
                    [
                        'model_version' => 'claude-3-5-sonnet-20241022',
                        'intent' => (string) ($data['intent'] ?? 'Inquiry'),
                        'urgency_level' => in_array($data['urgency_level'] ?? '', ['urgent', 'important', 'routine'], true)
                            ? $data['urgency_level']
                            : 'routine',
                        'urgency_score' => (float) ($data['urgency_score'] ?? 1.0),
                        'sentiment' => (string) ($data['sentiment'] ?? 'neutral'),
                        'deadline_detected' => $deadline,
                        'suggested_labels' => (array) ($data['suggested_labels'] ?? ['INBOX']),
                        'summary' => (string) ($data['summary'] ?? ''),
                        'requires_reply' => (bool) ($data['requires_reply'] ?? false),
                        'tokens_used' => $response->usage?->totalTokens ?? null,
                        'raw_response' => $data,
                    ]
                );

                AuditLog::record(
                    user: $emailMessage->user_id,
                    eventType: 'email.analyzed',
                    emailMessageId: $emailMessage->id,
                    metadata: [
                        'intent' => $analysis->intent,
                        'urgency_level' => $analysis->urgency_level,
                        'urgency_score' => $analysis->urgency_score,
                        'labels' => $analysis->suggested_labels,
                    ]
                );

                $proposedReply = $data['proposed_reply'] ?? null;
                if ($analysis->requires_reply && ! empty($proposedReply)) {
                    $draft = EmailDraft::updateOrCreate(
                        ['email_message_id' => $emailMessage->id],
                        [
                            'ai_analysis_id' => $analysis->id,
                            'proposed_body' => trim((string) $proposedReply),
                            'status' => 'pending',
                        ]
                    );

                    AuditLog::record(
                        user: $emailMessage->user_id,
                        eventType: 'draft.proposed',
                        emailMessageId: $emailMessage->id,
                        metadata: [
                            'draft_id' => $draft->id,
                            'preview' => mb_substr($draft->proposed_body, 0, 100),
                        ]
                    );
                }

                return $analysis;
            });
        } catch (Throwable $e) {
            Log::error('AI email analysis failed: '.$e->getMessage(), [
                'email_message_id' => $emailMessage->id,
                'exception' => $e,
            ]);

            AuditLog::record(
                user: $emailMessage->user_id,
                eventType: 'email.analysis_failed',
                emailMessageId: $emailMessage->id,
                metadata: [
                    'error' => $e->getMessage(),
                ]
            );

            throw $e;
        }
    }

    /**
     * Build the structured text prompt representing the incoming email.
     */
    protected function buildPrompt(EmailMessage $emailMessage): string
    {
        $body = ! empty($emailMessage->body_plain)
            ? $emailMessage->body_plain
            : strip_tags((string) $emailMessage->body_html);

        $receivedAt = $emailMessage->received_at?->toIso8601String() ?? 'Unknown';

        return <<<PROMPT
Sender Name: {$emailMessage->sender_name}
Sender Email: {$emailMessage->sender_email}
Recipient Email: {$emailMessage->recipient_email}
Received At: {$receivedAt}
Subject: {$emailMessage->subject}
Has Attachments: {$emailMessage->has_attachments}
Snippet: {$emailMessage->snippet}

Full Email Content:
{$body}
PROMPT;
    }
}
