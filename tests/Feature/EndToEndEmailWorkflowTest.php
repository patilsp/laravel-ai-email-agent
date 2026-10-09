<?php

namespace Tests\Feature;

use App\Ai\Agents\EmailTriageAgent;
use App\Jobs\PollGmailInboxJob;
use App\Models\EmailMessage;
use App\Models\OAuthToken;
use App\Models\User;
use App\Services\GmailApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EndToEndEmailWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_end_to_end_email_triage_review_and_dispatch_workflow(): void
    {
        // 1. Setup authenticated user with active Google OAuth token
        $user = User::factory()->create([
            'name' => 'Executive Officer',
            'email' => 'executive@enterprise.com',
        ]);

        OAuthToken::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
            'access_token' => 'mock_google_oauth_token',
            'refresh_token' => 'mock_google_refresh_token',
            'expires_at' => now()->addHour(),
        ]);

        // 2. Prepare incoming email payload from Gmail API
        $rawEmailContent = 'Hello, We are reviewing our Q4 vendor contracts and need your revised proposal by Friday 5 PM. Please let us know if any terms changed.';
        $encodedBody = rtrim(strtr(base64_encode($rawEmailContent), '+/', '-_'), '=');

        Http::fake([
            // Label discovery & creation
            'https://gmail.googleapis.com/gmail/v1/users/me/labels*' => Http::response([
                'labels' => [['id' => 'Label_AI_PROCESSED', 'name' => 'AI_PROCESSED']],
            ], 200),
            // Outbound reply send
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send*' => Http::response([
                'id' => 'sent_dispatch_e2e_555',
                'threadId' => 'thd_vendor_101',
            ], 200),
            // Message detail retrieval
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/msg_vendor_101/modify' => Http::response([
                'id' => 'msg_vendor_101',
            ], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/msg_vendor_101*' => Http::response([
                'id' => 'msg_vendor_101',
                'threadId' => 'thd_vendor_101',
                'snippet' => 'Hello, We are reviewing our Q4 vendor contracts...',
                'internalDate' => '1770000000000',
                'payload' => [
                    'headers' => [
                        ['name' => 'From', 'value' => '"David Miller" <david@procurement.com>'],
                        ['name' => 'To', 'value' => 'executive@enterprise.com'],
                        ['name' => 'Subject', 'value' => 'Q4 Vendor Contract Deadline'],
                    ],
                    'parts' => [
                        [
                            'mimeType' => 'text/plain',
                            'filename' => '',
                            'body' => ['data' => $encodedBody],
                        ],
                    ],
                ],
            ], 200),
            // Message query
            'https://gmail.googleapis.com/gmail/v1/users/me/messages*' => Http::response([
                'messages' => [
                    ['id' => 'msg_vendor_101', 'threadId' => 'thd_vendor_101'],
                ],
            ], 200),
        ]);

        // Mock Claude 3.5 Sonnet structured intelligence response
        $mockClaudeAnalysis = [
            'intent' => 'Action Required',
            'urgency_level' => 'urgent',
            'urgency_score' => 4.9,
            'urgency_reason' => 'Contract revision requested with strict Friday 5 PM deadline.',
            'sentiment' => 'urgent',
            'deadline_detected' => '2026-10-16T17:00:00Z',
            'suggested_labels' => ['Procurement', 'Contracts', 'Urgent'],
            'summary' => 'David is requesting our revised Q4 vendor proposal before Friday at 5 PM.',
            'requires_reply' => true,
            'proposed_reply' => "Hi David,\n\nThanks for following up. We are finalizing the revised Q4 vendor proposal and will deliver it well ahead of the Friday 5 PM deadline.\n\nBest,\nExecutive Team",
            'key_action_items' => ['Finalize Q4 proposal', 'Send before Friday 5 PM'],
        ];

        EmailTriageAgent::fake([json_encode($mockClaudeAnalysis)]);

        // 3. Execute Step 1: Poll Gmail Inbox Job
        $pollJob = new PollGmailInboxJob($user);
        $pollJob->handle(app(GmailApiService::class));

        $emailMessage = EmailMessage::where('gmail_message_id', 'msg_vendor_101')->first();
        $this->assertNotNull($emailMessage);
        $this->assertEquals('David Miller', $emailMessage->sender_name);
        $this->assertEquals('david@procurement.com', $emailMessage->sender_email);

        // 4. Verify AI Analysis & Draft created automatically via queued job
        $emailMessage->refresh();
        $this->assertNotNull($emailMessage->aiAnalysis);
        $this->assertEquals('urgent', $emailMessage->aiAnalysis->urgency_level);
        $this->assertEquals('4.9', (string) $emailMessage->aiAnalysis->urgency_score);

        $draft = $emailMessage->draft;
        $this->assertNotNull($draft);
        $this->assertTrue($draft->isPending());
        $this->assertStringContainsString('We are finalizing the revised Q4 vendor proposal', $draft->proposed_body);

        // 5. Execute Step 3: User views dashboard workspace
        $dashboardResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashboardResponse->assertOk()
            ->assertSeeText('Q4 Vendor Contract Deadline')
            ->assertSeeText('David Miller')
            ->assertSeeText('David is requesting our revised Q4 vendor proposal')
            ->assertSeeText('Approve & Send', false);

        // 6. Execute Step 4: User customizes draft with human revision
        $revisedBody = "Hi David,\n\nWe have updated section 4 with discounted volume pricing. The proposal is attached.\n\nBest regards,\nExecutive Team";

        $editResponse = $this->actingAs($user)->putJson(route('drafts.edit', $draft), [
            'body' => $revisedBody,
        ]);
        $editResponse->assertOk();

        $draft->refresh();
        $this->assertTrue($draft->isEdited());
        $this->assertEquals($revisedBody, $draft->effective_body);

        // 7. Execute Step 5: User approves and sends draft via Gmail API
        $approveResponse = $this->actingAs($user)->postJson(route('drafts.approve', $draft));
        $approveResponse->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Email reply sent successfully.',
            ]);

        $draft->refresh();
        $this->assertTrue($draft->isSent());
        $this->assertEquals('sent_dispatch_e2e_555', $draft->gmail_sent_message_id);
        $this->assertNotNull($draft->sent_at);

        // 8. Verify full audit trail
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $emailMessage->id,
            'event_type' => 'email.received',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $emailMessage->id,
            'event_type' => 'email.analyzed',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $emailMessage->id,
            'event_type' => 'draft.proposed',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $emailMessage->id,
            'event_type' => 'draft.edited',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'email_message_id' => $emailMessage->id,
            'event_type' => 'email.sent',
        ]);
    }
}
