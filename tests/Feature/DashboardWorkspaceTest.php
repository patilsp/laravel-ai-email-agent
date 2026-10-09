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

class DashboardWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'executive@company.com']);
        OAuthToken::factory()->create([
            'user_id' => $this->user->id,
            'provider' => 'google',
            'access_token' => 'mock_token',
            'refresh_token' => 'mock_refresh',
            'expires_at' => now()->addHour(),
        ]);
    }

    public function test_dashboard_displays_inbox_stats_and_messages_for_connected_user(): void
    {
        $message = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'sender_name' => 'Michael Scott',
            'subject' => 'Quarterly Financial Review',
        ]);

        $analysis = AiAnalysis::factory()->urgent()->create([
            'email_message_id' => $message->id,
            'summary' => 'Urgent budget review needed.',
        ]);

        $draft = EmailDraft::factory()->create([
            'email_message_id' => $message->id,
            'ai_analysis_id' => $analysis->id,
            'proposed_body' => 'I will review the numbers today.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertOk()
            ->assertSeeText('Quarterly Financial Review')
            ->assertSeeText('Michael Scott')
            ->assertSeeText('Urgent budget review needed.')
            ->assertSeeText('I will review the numbers today.')
            ->assertSeeText('Approve & Send', false);
    }

    public function test_dashboard_filters_urgent_emails(): void
    {
        $urgentMsg = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'subject' => 'URGENT: Server Down',
        ]);
        AiAnalysis::factory()->urgent()->create(['email_message_id' => $urgentMsg->id]);

        $routineMsg = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'subject' => 'Casual Newsletter',
        ]);
        AiAnalysis::factory()->noReplyRequired()->create(['email_message_id' => $routineMsg->id]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard', ['filter' => 'urgent']));

        $response->assertOk()
            ->assertSee('URGENT: Server Down')
            ->assertDontSee('Casual Newsletter');
    }

    public function test_dashboard_filters_pending_drafts(): void
    {
        $msgWithDraft = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'subject' => 'Contract for Signing',
        ]);
        $analysis = AiAnalysis::factory()->create(['email_message_id' => $msgWithDraft->id]);
        EmailDraft::factory()->create([
            'email_message_id' => $msgWithDraft->id,
            'ai_analysis_id' => $analysis->id,
            'status' => 'pending',
        ]);

        $msgSent = EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'subject' => 'Old Completed Thread',
        ]);
        $analysisSent = AiAnalysis::factory()->create(['email_message_id' => $msgSent->id]);
        EmailDraft::factory()->sent()->create([
            'email_message_id' => $msgSent->id,
            'ai_analysis_id' => $analysisSent->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard', ['filter' => 'pending']));

        $response->assertOk()
            ->assertSee('Contract for Signing')
            ->assertDontSee('Old Completed Thread');
    }

    public function test_dashboard_search_filters_by_keyword(): void
    {
        EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'sender_name' => 'Elon Musk',
            'subject' => 'Starship Update',
        ]);

        EmailMessage::factory()->create([
            'user_id' => $this->user->id,
            'sender_name' => 'Bill Gates',
            'subject' => 'Foundation Annual Meeting',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard', ['q' => 'Starship']));

        $response->assertOk()
            ->assertSee('Starship Update')
            ->assertDontSee('Foundation Annual Meeting');
    }

    public function test_dashboard_manual_sync_triggers_gmail_poll(): void
    {
        Http::fake([
            'https://gmail.googleapis.com/gmail/v1/users/me/labels*' => Http::response(['labels' => []], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/labels' => Http::response(['id' => 'label_123'], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages*' => Http::response(['messages' => []], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('dashboard.sync'));

        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'Inbox sync completed successfully. New emails analyzed.');
    }
}
