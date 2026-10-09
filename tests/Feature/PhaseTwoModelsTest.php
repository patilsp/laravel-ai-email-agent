<?php

namespace Tests\Feature;

use App\Models\AiAnalysis;
use App\Models\AuditLog;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseTwoModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_message_can_be_created_with_factory_and_relationships(): void
    {
        $user = User::factory()->create();

        $message = EmailMessage::factory()->create([
            'user_id' => $user->id,
            'sender_name' => 'Alice Smith',
            'sender_email' => 'alice@example.com',
            'has_attachments' => true,
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('email_messages', [
            'id' => $message->id,
            'user_id' => $user->id,
            'sender_name' => 'Alice Smith',
        ]);

        $this->assertTrue($message->user->is($user));
        $this->assertTrue($message->has_attachments);
        $this->assertFalse($message->is_read);
        $this->assertEquals('Alice Smith', $message->sender_display);
        $this->assertFalse($message->isAnalyzed());

        // Test User -> EmailMessages relation
        $this->assertCount(1, $user->emailMessages);
        $this->assertTrue($user->emailMessages->first()->is($message));
    }

    public function test_email_message_scopes(): void
    {
        $user = User::factory()->create();

        $unread = EmailMessage::factory()->create([
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        $read = EmailMessage::factory()->read()->create([
            'user_id' => $user->id,
        ]);

        $urgentAnalysis = AiAnalysis::factory()->urgent()->create([
            'email_message_id' => $unread->id,
        ]);

        $this->assertCount(1, EmailMessage::unread()->get());
        $this->assertTrue(EmailMessage::unread()->first()->is($unread));

        $this->assertCount(1, EmailMessage::urgent()->get());
        $this->assertTrue(EmailMessage::urgent()->first()->is($unread));
        $this->assertTrue($unread->isAnalyzed());
    }

    public function test_ai_analysis_attributes_casts_and_helpers(): void
    {
        $message = EmailMessage::factory()->create();

        $analysis = AiAnalysis::factory()->create([
            'email_message_id' => $message->id,
            'urgency_level' => 'urgent',
            'urgency_score' => 4.8,
            'suggested_labels' => ['VIP', 'Inquiry'],
            'raw_response' => ['intent' => 'Urgent Request'],
            'requires_reply' => true,
        ]);

        $this->assertTrue($analysis->isUrgent());
        $this->assertFalse($analysis->isImportant());
        $this->assertIsArray($analysis->suggested_labels);
        $this->assertContains('VIP', $analysis->suggested_labels);
        $this->assertIsArray($analysis->raw_response);
        $this->assertEquals('Urgent Request', $analysis->raw_response['intent']);
        $this->assertTrue($analysis->emailMessage->is($message));

        $this->assertCount(1, AiAnalysis::urgent()->get());
        $this->assertCount(1, AiAnalysis::requiringReply()->get());
    }

    public function test_email_draft_state_transitions_and_effective_body(): void
    {
        $message = EmailMessage::factory()->create();
        $analysis = AiAnalysis::factory()->create(['email_message_id' => $message->id]);

        $draft = EmailDraft::factory()->create([
            'email_message_id' => $message->id,
            'ai_analysis_id' => $analysis->id,
            'proposed_body' => 'Original AI proposed body.',
            'edited_body' => null,
            'status' => 'pending',
        ]);

        $this->assertTrue($draft->isPending());
        $this->assertEquals('Original AI proposed body.', $draft->effective_body);

        // Transition to edited
        $draft->markEdited('Human edited body content.');
        $draft->refresh();

        $this->assertTrue($draft->isEdited());
        $this->assertEquals('Human edited body content.', $draft->effective_body);
        $this->assertEquals('Original AI proposed body.', $draft->proposed_body);

        // Transition to approved
        $draft->markApproved();
        $draft->refresh();
        $this->assertTrue($draft->isApproved());

        // Transition to sent
        $draft->markSent('gmail_msg_abc123');
        $draft->refresh();
        $this->assertTrue($draft->isSent());
        $this->assertNotNull($draft->sent_at);
        $this->assertEquals('gmail_msg_abc123', $draft->gmail_sent_message_id);

        // Test rejecting
        $draft2 = EmailDraft::factory()->create();
        $draft2->markRejected();
        $this->assertTrue($draft2->isRejected());
    }

    public function test_audit_log_record_helper_and_relationships(): void
    {
        $user = User::factory()->create();
        $message = EmailMessage::factory()->create(['user_id' => $user->id]);

        $log = AuditLog::record(
            user: $user,
            eventType: 'draft.approved',
            emailMessageId: $message->id,
            metadata: ['editor' => 'human', 'confidence' => 0.95]
        );

        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'user_id' => $user->id,
            'email_message_id' => $message->id,
            'event_type' => 'draft.approved',
        ]);

        $this->assertTrue($log->user->is($user));
        $this->assertTrue($log->emailMessage->is($message));
        $this->assertIsArray($log->metadata);
        $this->assertEquals('human', $log->metadata['editor']);

        // Test User -> AuditLogs relation
        $this->assertCount(1, $user->auditLogs);
        $this->assertTrue($user->auditLogs->first()->is($log));
    }
}
