<?php

namespace Tests\Feature;

use App\Ai\Agents\EmailTriageAgent;
use App\Jobs\AnalyzeEmailJob;
use App\Models\AiAnalysis;
use App\Models\EmailMessage;
use App\Models\User;
use App\Services\EmailAnalyzerService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeTriageTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_analyzer_service_processes_email_and_creates_analysis_and_draft(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create([
            'user_id' => $user->id,
            'sender_name' => 'Sarah Connor',
            'sender_email' => 'sarah@cyberdyne.com',
            'subject' => 'Urgent: Contract review before 5 PM today',
            'body_plain' => 'Hi John, Could you please review the attached agreement before 5 PM today? Let me know if any clauses need adjustment.',
        ]);

        $mockResponse = [
            'intent' => 'Action Required',
            'urgency_level' => 'urgent',
            'urgency_score' => 4.8,
            'urgency_reason' => 'Sender has an explicit 5 PM deadline today for contract approval.',
            'sentiment' => 'urgent',
            'deadline_detected' => '2026-10-09T17:00:00Z',
            'suggested_labels' => ['Client', 'Contracts', 'Urgent'],
            'summary' => 'Sarah is requesting urgent review of the contract before 5 PM today.',
            'requires_reply' => true,
            'proposed_reply' => "Hi Sarah,\n\nI will review the contract clauses immediately and get back to you with comments well before 5 PM.\n\nBest regards,\nJohn",
            'key_action_items' => ['Review contract clauses', 'Respond before 5 PM'],
        ];

        EmailTriageAgent::fake([json_encode($mockResponse)]);

        $service = app(EmailAnalyzerService::class);
        $analysis = $service->analyze($message);

        // Verify Analysis
        $this->assertInstanceOf(AiAnalysis::class, $analysis);
        $this->assertEquals($message->id, $analysis->email_message_id);
        $this->assertEquals('Action Required', $analysis->intent);
        $this->assertEquals('urgent', $analysis->urgency_level);
        $this->assertEquals('4.8', (string) $analysis->urgency_score);
        $this->assertTrue($analysis->isUrgent());
        $this->assertTrue($analysis->requires_reply);
        $this->assertContains('Contracts', $analysis->suggested_labels);
        $this->assertNotNull($analysis->deadline_detected);

        // Verify Draft
        $this->assertDatabaseHas('email_drafts', [
            'email_message_id' => $message->id,
            'ai_analysis_id' => $analysis->id,
            'status' => 'pending',
        ]);

        $draft = $message->draft;
        $this->assertNotNull($draft);
        $this->assertStringContainsString('I will review the contract clauses immediately', $draft->proposed_body);
        $this->assertTrue($draft->isPending());

        // Verify Audit Logs
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'email.analyzed',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'draft.proposed',
        ]);

        EmailTriageAgent::assertPrompted(function ($prompt) use ($message) {
            return str_contains($prompt->prompt, $message->subject)
                && str_contains($prompt->prompt, 'sarah@cyberdyne.com');
        });
    }

    public function test_email_analyzer_service_skips_draft_when_no_reply_required(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create([
            'user_id' => $user->id,
            'subject' => 'Your weekly digest summary',
            'body_plain' => 'Here are the top news headlines for this week...',
        ]);

        $mockResponse = [
            'intent' => 'Newsletter',
            'urgency_level' => 'routine',
            'urgency_score' => 1.0,
            'urgency_reason' => 'Automated newsletter with no action items.',
            'sentiment' => 'neutral',
            'deadline_detected' => null,
            'suggested_labels' => ['Newsletters'],
            'summary' => 'Weekly informational news digest.',
            'requires_reply' => false,
            'proposed_reply' => null,
            'key_action_items' => [],
        ];

        EmailTriageAgent::fake([json_encode($mockResponse)]);

        $service = app(EmailAnalyzerService::class);
        $analysis = $service->analyze($message);

        $this->assertInstanceOf(AiAnalysis::class, $analysis);
        $this->assertFalse($analysis->requires_reply);
        $this->assertEquals('routine', $analysis->urgency_level);

        // No draft created
        $this->assertDatabaseMissing('email_drafts', [
            'email_message_id' => $message->id,
        ]);

        // Analyzed audit log exists, but no draft.proposed log
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'email.analyzed',
        ]);

        $this->assertDatabaseMissing('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'draft.proposed',
        ]);
    }

    public function test_email_analyzer_service_records_audit_log_on_failure(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create([
            'user_id' => $user->id,
        ]);

        EmailTriageAgent::fake([
            function () {
                throw new Exception('Anthropic API connection timeout');
            },
        ]);

        $service = app(EmailAnalyzerService::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Anthropic API connection timeout');

        try {
            $service->analyze($message);
        } finally {
            $this->assertDatabaseHas('audit_logs', [
                'user_id' => $user->id,
                'email_message_id' => $message->id,
                'event_type' => 'email.analysis_failed',
            ]);
        }
    }

    public function test_analyze_email_job_processes_email_message(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create(['user_id' => $user->id]);

        $mockResponse = [
            'intent' => 'Meeting Request',
            'urgency_level' => 'important',
            'urgency_score' => 3.5,
            'urgency_reason' => 'Partnership meeting request for next week.',
            'sentiment' => 'positive',
            'deadline_detected' => '2026-10-15T14:00:00Z',
            'suggested_labels' => ['Meetings', 'Partnerships'],
            'summary' => 'Invitation to discuss Q4 integration partnership.',
            'requires_reply' => true,
            'proposed_reply' => 'Hi, thanks for reaching out. Thursday at 2 PM works great.',
            'key_action_items' => ['Confirm calendar availability'],
        ];

        EmailTriageAgent::fake([json_encode($mockResponse)]);

        $job = new AnalyzeEmailJob($message);
        $job->handle(app(EmailAnalyzerService::class));

        $this->assertDatabaseHas('ai_analyses', [
            'email_message_id' => $message->id,
            'intent' => 'Meeting Request',
            'urgency_level' => 'important',
        ]);

        $this->assertDatabaseHas('email_drafts', [
            'email_message_id' => $message->id,
            'status' => 'pending',
        ]);
    }

    public function test_analyze_email_job_failed_hook_records_audit_log(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create(['user_id' => $user->id]);

        $job = new AnalyzeEmailJob($message);
        $job->failed(new Exception('Exhausted maximum retries'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'email.analysis_exhausted',
        ]);
    }
}
