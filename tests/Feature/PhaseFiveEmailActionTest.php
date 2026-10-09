<?php

namespace Tests\Feature;

use App\Models\AiAnalysis;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PhaseFiveEmailActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'sender@example.com']);
        OAuthToken::factory()->create([
            'user_id' => $this->user->id,
            'provider' => 'google',
            'access_token' => 'mock_token',
            'refresh_token' => 'mock_refresh',
            'expires_at' => now()->addHour(),
        ]);
    }

    public function test_user_can_approve_and_send_draft(): void
    {
        $message = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'sender_email' => 'client@target.com',
            'subject' => 'Quote inquiry for services',
            'gmail_message_id' => 'msg_inquiry_100',
            'gmail_thread_id' => 'thd_inquiry_100',
        ]);

        $analysis = AiAnalysis::factory()->create(['email_message_id' => $message->id]);

        $draft = EmailDraft::factory()->create([
            'email_message_id' => $message->id,
            'ai_analysis_id' => $analysis->id,
            'proposed_body' => 'Here is our standard pricing deck attached.',
            'status' => 'pending',
        ]);

        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send' => Http::response([
                'id' => 'sent_dispatch_999',
                'threadId' => 'thd_inquiry_100',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('drafts.approve', $draft));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Email reply sent successfully.',
            ]);

        $draft->refresh();
        $this->assertTrue($draft->isSent());
        $this->assertEquals('sent_dispatch_999', $draft->gmail_sent_message_id);
        $this->assertNotNull($draft->sent_at);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'email_message_id' => $message->id,
            'event_type' => 'email.sent',
        ]);
    }

    public function test_user_can_edit_draft_body(): void
    {
        $message = EmailMessage::factory()->create(['user_id' => $this->user->id]);
        $draft = EmailDraft::factory()->create([
            'email_message_id' => $message->id,
            'proposed_body' => 'Original text.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->putJson(route('drafts.edit', $draft), [
                'body' => 'Polished custom human revision.',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Draft updated successfully.',
            ]);

        $draft->refresh();
        $this->assertTrue($draft->isEdited());
        $this->assertEquals('Polished custom human revision.', $draft->effective_body);
        $this->assertEquals('Original text.', $draft->proposed_body);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'email_message_id' => $message->id,
            'event_type' => 'draft.edited',
        ]);
    }

    public function test_user_can_reject_draft(): void
    {
        $message = EmailMessage::factory()->create(['user_id' => $this->user->id]);
        $draft = EmailDraft::factory()->create([
            'email_message_id' => $message->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('drafts.reject', $draft));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Draft rejected.',
            ]);

        $draft->refresh();
        $this->assertTrue($draft->isRejected());

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'email_message_id' => $message->id,
            'event_type' => 'draft.rejected',
        ]);
    }

    public function test_user_can_apply_suggested_labels(): void
    {
        $message = EmailMessage::factory()->create(['user_id' => $this->user->id]);
        AiAnalysis::factory()->create([
            'email_message_id' => $message->id,
            'suggested_labels' => ['VIP Client', 'Inquiries'],
        ]);

        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/labels*' => Http::response([
                'labels' => [
                    ['id' => 'Label_VIP', 'name' => 'VIP Client'],
                    ['id' => 'Label_Inq', 'name' => 'Inquiries'],
                ],
            ], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/*/modify' => Http::response([
                'id' => $message->gmail_message_id,
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('emails.labels', $message));

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'email_message_id' => $message->id,
            'event_type' => 'labels.applied',
        ]);
    }

    public function test_user_can_trash_email(): void
    {
        $message = EmailMessage::factory()->create(['user_id' => $this->user->id]);

        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/*/trash' => Http::response([], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('emails.trash', $message));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Email moved to Trash.',
            ]);

        $this->assertDatabaseMissing('email_messages', [
            'id' => $message->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'event_type' => 'email.trashed',
        ]);
    }

    public function test_user_can_compose_and_send_custom_email(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send' => Http::response([
                'id' => 'sent_custom_333',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('emails.send'), [
                'to' => 'partner@company.com',
                'subject' => 'Introduction & Partnership',
                'body' => 'Hello, I wanted to introduce our solution.',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Custom email sent successfully.',
            ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'event_type' => 'email.composed_and_sent',
        ]);
    }

    public function test_user_cannot_act_on_another_users_draft_or_email(): void
    {
        $otherUser = User::factory()->create();
        $message = EmailMessage::factory()->create(['user_id' => $otherUser->id]);
        $draft = EmailDraft::factory()->create(['email_message_id' => $message->id]);

        // Attempt approve
        $this->actingAs($this->user)
            ->postJson(route('drafts.approve', $draft))
            ->assertForbidden();

        // Attempt edit
        $this->actingAs($this->user)
            ->putJson(route('drafts.edit', $draft), ['body' => 'Hacked body'])
            ->assertForbidden();

        // Attempt reject
        $this->actingAs($this->user)
            ->postJson(route('drafts.reject', $draft))
            ->assertForbidden();

        // Attempt trash
        $this->actingAs($this->user)
            ->deleteJson(route('emails.trash', $message))
            ->assertForbidden();
    }
}
