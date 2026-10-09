<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\EmailMessage;
use App\Models\User;
use App\Services\GmailApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class PollGmailInboxJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var list<int>
     */
    public array $backoff = [15, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GmailApiService $gmailService): void
    {
        if (! $this->user->isConnectedToGoogle()) {
            Log::info("Skipping PollGmailInboxJob for user #{$this->user->id}: No active Google connection.");

            return;
        }

        try {
            $processedLabelId = null;
            try {
                $processedLabelId = $gmailService->getOrCreateLabel($this->user, 'AI_PROCESSED');
            } catch (Throwable $e) {
                Log::warning("Could not ensure AI_PROCESSED label: {$e->getMessage()}");
            }

            $messages = $gmailService->listMessages($this->user, 'is:unread -label:AI_PROCESSED', 20);

            if (empty($messages)) {
                $messages = $gmailService->listMessages($this->user, 'in:inbox -label:AI_PROCESSED', 20);
            }

            foreach ($messages as $msgSummary) {
                $gmailMessageId = (string) ($msgSummary['id'] ?? '');

                if (empty($gmailMessageId)) {
                    continue;
                }

                // Deduplicate if already stored
                if (EmailMessage::where('gmail_message_id', $gmailMessageId)->exists()) {
                    continue;
                }

                $raw = $gmailService->getMessage($this->user, $gmailMessageId);
                $parsed = $gmailService->parseMessagePayload($raw);

                $emailMessage = EmailMessage::create([
                    'user_id' => $this->user->id,
                    'gmail_message_id' => $parsed['gmail_message_id'],
                    'gmail_thread_id' => $parsed['gmail_thread_id'],
                    'sender_name' => $parsed['sender_name'],
                    'sender_email' => $parsed['sender_email'],
                    'recipient_email' => $parsed['recipient_email'],
                    'subject' => $parsed['subject'],
                    'snippet' => $parsed['snippet'],
                    'body_plain' => $parsed['body_plain'],
                    'body_html' => $parsed['body_html'],
                    'has_attachments' => $parsed['has_attachments'],
                    'received_at' => $parsed['received_at'],
                    'is_read' => false,
                ]);

                // Tag in Gmail to avoid re-pulling
                if ($processedLabelId) {
                    try {
                        $gmailService->applyLabels($this->user, $gmailMessageId, [$processedLabelId]);
                    } catch (Throwable $e) {
                        Log::warning("Could not apply AI_PROCESSED label to message #{$gmailMessageId}: ".$e->getMessage());
                    }
                }

                AuditLog::record(
                    user: $this->user->id,
                    eventType: 'email.received',
                    emailMessageId: $emailMessage->id,
                    metadata: [
                        'subject' => $emailMessage->subject,
                        'sender' => $emailMessage->sender_email,
                    ]
                );

                // Dispatch AI Triage Analysis Job
                AnalyzeEmailJob::dispatch($emailMessage);
            }
        } catch (Throwable $e) {
            Log::error("PollGmailInboxJob failed for user #{$this->user->id}: ".$e->getMessage(), [
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
