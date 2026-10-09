<?php

namespace Tests\Feature;

use App\Jobs\AnalyzeEmailJob;
use App\Jobs\PollGmailInboxJob;
use App\Models\EmailMessage;
use App\Models\OAuthToken;
use App\Models\User;
use App\Services\GmailApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PhaseFourGmailSyncTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'user@example.com']);
        OAuthToken::factory()->create([
            'user_id' => $this->user->id,
            'provider' => 'google',
            'access_token' => 'mock_google_access_token',
            'refresh_token' => 'mock_refresh_token',
            'expires_at' => now()->addHour(),
        ]);
    }

    public function test_gmail_api_service_list_messages_returns_message_stubs(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages*' => Http::response([
                'messages' => [
                    ['id' => 'msg_101', 'threadId' => 'thd_201'],
                    ['id' => 'msg_102', 'threadId' => 'thd_202'],
                ],
                'resultSizeEstimate' => 2,
            ], 200),
        ]);

        $service = app(GmailApiService::class);
        $messages = $service->listMessages($this->user);

        $this->assertCount(2, $messages);
        $this->assertEquals('msg_101', $messages[0]['id']);
        $this->assertEquals('thd_201', $messages[0]['threadId']);
    }

    public function test_gmail_api_service_parses_full_mime_payload_correctly(): void
    {
        $rawMessage = [
            'id' => 'msg_abc999',
            'threadId' => 'thd_xyz888',
            'snippet' => 'Hey, when can we meet?',
            'internalDate' => '1770000000000',
            'payload' => [
                'headers' => [
                    ['name' => 'From', 'value' => '"Jane Doe" <jane@acme.corp>'],
                    ['name' => 'To', 'value' => 'user@example.com'],
                    ['name' => 'Subject', 'value' => 'Project Timeline Update'],
                ],
                'parts' => [
                    [
                        'mimeType' => 'text/plain',
                        'filename' => '',
                        'body' => [
                            // Base64URL for "Let's schedule a call tomorrow at 10 AM."
                            'data' => rtrim(strtr(base64_encode("Let's schedule a call tomorrow at 10 AM."), '+/', '-_'), '='),
                        ],
                    ],
                    [
                        'mimeType' => 'application/pdf',
                        'filename' => 'timeline.pdf',
                        'body' => [
                            'attachmentId' => 'att_file_123',
                            'size' => 10240,
                        ],
                    ],
                ],
            ],
        ];

        $service = app(GmailApiService::class);
        $parsed = $service->parseMessagePayload($rawMessage);

        $this->assertEquals('msg_abc999', $parsed['gmail_message_id']);
        $this->assertEquals('thd_xyz888', $parsed['gmail_thread_id']);
        $this->assertEquals('Jane Doe', $parsed['sender_name']);
        $this->assertEquals('jane@acme.corp', $parsed['sender_email']);
        $this->assertEquals('user@example.com', $parsed['recipient_email']);
        $this->assertEquals('Project Timeline Update', $parsed['subject']);
        $this->assertEquals("Let's schedule a call tomorrow at 10 AM.", $parsed['body_plain']);
        $this->assertTrue($parsed['has_attachments']);
        $this->assertNotNull($parsed['received_at']);
    }

    public function test_gmail_api_service_label_operations(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/labels' => Http::sequence()
                // 1. Initial list has INBOX but not AI_PROCESSED
                ->push(['labels' => [['id' => 'Label_1', 'name' => 'INBOX']]], 200)
                // 2. Create AI_PROCESSED label response
                ->push(['id' => 'Label_AI_PROCESSED', 'name' => 'AI_PROCESSED'], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/msg_123/modify' => Http::response([
                'id' => 'msg_123',
                'labelIds' => ['Label_AI_PROCESSED'],
            ], 200),
        ]);

        $service = app(GmailApiService::class);
        $labelId = $service->getOrCreateLabel($this->user, 'AI_PROCESSED');

        $this->assertEquals('Label_AI_PROCESSED', $labelId);

        $modifyRes = $service->applyLabels($this->user, 'msg_123', ['Label_AI_PROCESSED']);
        $this->assertEquals('msg_123', $modifyRes['id']);
    }

    public function test_gmail_api_service_send_message_dispatches_rfc_format(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send' => Http::response([
                'id' => 'sent_msg_777',
                'threadId' => 'thd_xyz888',
                'labelIds' => ['SENT'],
            ], 200),
        ]);

        $service = app(GmailApiService::class);
        $result = $service->sendMessage(
            user: $this->user,
            to: 'client@example.com',
            subject: 'Re: Agreement details',
            body: 'Here is the approved agreement.',
            threadId: 'thd_xyz888',
            inReplyTo: 'msg_orig_111'
        );

        $this->assertEquals('sent_msg_777', $result['id']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/messages/send')
                && ! empty($request['raw'])
                && $request['threadId'] === 'thd_xyz888';
        });
    }

    public function test_gmail_api_service_trash_and_delete_message(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/msg_trash/trash' => Http::response([], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/msg_delete' => Http::response([], 204),
        ]);

        $service = app(GmailApiService::class);

        $this->assertTrue($service->trashMessage($this->user, 'msg_trash'));
        $this->assertTrue($service->deleteMessage($this->user, 'msg_delete'));
    }

    public function test_poll_gmail_inbox_job_ingests_messages_and_dispatches_triage(): void
    {
        Queue::fake([AnalyzeEmailJob::class]);

        $plainBody = 'Hi there, I would like to schedule a demo of your AI product.';
        $encodedBody = rtrim(strtr(base64_encode($plainBody), '+/', '-_'), '=');

        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/labels*' => Http::response([
                'labels' => [['id' => 'Label_AI_PROCESSED', 'name' => 'AI_PROCESSED']],
            ], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/gmail_msg_001/modify' => Http::response([
                'id' => 'gmail_msg_001',
            ], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/gmail_msg_001*' => Http::response([
                'id' => 'gmail_msg_001',
                'threadId' => 'gmail_thd_001',
                'snippet' => 'Hi there, I would like to schedule a demo...',
                'internalDate' => '1770000000000',
                'payload' => [
                    'headers' => [
                        ['name' => 'From', 'value' => '"Mark Davis" <mark@enterprise.com>'],
                        ['name' => 'To', 'value' => 'user@example.com'],
                        ['name' => 'Subject', 'value' => 'Product Demo Inquiry'],
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
            'https://gmail.googleapis.com/gmail/v1/users/me/messages*' => Http::response([
                'messages' => [
                    ['id' => 'gmail_msg_001', 'threadId' => 'gmail_thd_001'],
                ],
            ], 200),
        ]);

        $job = new PollGmailInboxJob($this->user);
        $job->handle(app(GmailApiService::class));

        // Check EmailMessage created
        $this->assertDatabaseHas('email_messages', [
            'user_id' => $this->user->id,
            'gmail_message_id' => 'gmail_msg_001',
            'gmail_thread_id' => 'gmail_thd_001',
            'sender_email' => 'mark@enterprise.com',
            'subject' => 'Product Demo Inquiry',
            'is_read' => false,
        ]);

        // Check AuditLog recorded
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->user->id,
            'event_type' => 'email.received',
        ]);

        // Assert AnalyzeEmailJob was dispatched
        Queue::assertPushed(AnalyzeEmailJob::class, function (AnalyzeEmailJob $job) {
            return $job->emailMessage->gmail_message_id === 'gmail_msg_001';
        });

        // Test deduplication on subsequent poll
        $job->handle(app(GmailApiService::class));
        $this->assertDatabaseCount('email_messages', 1);
    }

    public function test_sync_gmail_inbox_command_dispatches_jobs_for_connected_users(): void
    {
        Queue::fake([PollGmailInboxJob::class]);

        // User without token
        User::factory()->create(['email' => 'unconnected@example.com']);

        // Run sync for all
        $this->artisan('email:sync')
            ->expectsOutputToContain('Successfully scheduled inbox sync for 1 connected user(s).')
            ->assertSuccessful();

        Queue::assertPushed(PollGmailInboxJob::class, 1);

        // Run sync for specific user
        $this->artisan('email:sync', ['--user' => $this->user->id])
            ->expectsOutputToContain("Dispatched Gmail sync job for user #{$this->user->id}")
            ->assertSuccessful();

        Queue::assertPushed(PollGmailInboxJob::class, 2);
    }
}
