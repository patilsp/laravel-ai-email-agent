<?php

namespace Database\Seeders;

use App\Models\AiAnalysis;
use App\Models\AuditLog;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@ai-email-agent.local'],
            [
                'name' => 'Executive Demo User',
                'password' => bcrypt('password'),
            ]
        );

        self::seedUser($user);
    }

    /**
     * Populate a user with realistic demo emails, AI analysis, drafts, and audit trails.
     */
    public static function seedUser(User $user): void
    {
        OAuthToken::updateOrCreate(
            ['user_id' => $user->id, 'provider' => 'google'],
            [
                'access_token' => 'mock_demo_access_token',
                'refresh_token' => 'mock_demo_refresh_token',
                'expires_at' => now()->addMonth(),
                'scopes' => config('services.google.scopes', []),
            ]
        );

        // 1. Urgent Contract Review
        $msg1 = EmailMessage::firstOrCreate(
            ['gmail_message_id' => 'msg_demo_001'],
            [
                'user_id' => $user->id,
                'gmail_thread_id' => 'thd_demo_001',
                'sender_name' => 'Sarah Jenkins',
                'sender_email' => 's.jenkins@acmecorp.io',
                'recipient_email' => $user->email,
                'subject' => 'Urgent: Contract review before 5 PM today',
                'snippet' => 'Hi John, We need your final sign-off on the Master Service Agreement before 5 PM today...',
                'body_plain' => "Hi John,\n\nWe need your final sign-off on the Master Service Agreement before 5 PM today to lock in the special Q4 volume tier.\n\nPlease review Section 4 (Service Levels) and let me know if we can proceed with signatures.\n\nBest regards,\nSarah Jenkins\nVP Partnerships, Acme Corp",
                'has_attachments' => true,
                'received_at' => now()->subMinutes(15),
                'is_read' => false,
            ]
        );

        $analysis1 = AiAnalysis::updateOrCreate(
            ['email_message_id' => $msg1->id],
            [
                'model_version' => 'claude-3-5-sonnet-20241022',
                'intent' => 'Action Required',
                'urgency_level' => 'urgent',
                'urgency_score' => 4.9,
                'sentiment' => 'urgent',
                'deadline_detected' => now()->setTime(17, 0, 0),
                'suggested_labels' => ['Client', 'Contracts', 'Urgent'],
                'summary' => 'Sarah needs urgent sign-off on the MSA Section 4 before 5 PM today to lock in Q4 volume pricing.',
                'requires_reply' => true,
                'tokens_used' => 380,
                'raw_response' => ['intent' => 'Action Required', 'urgency_score' => 4.9],
            ]
        );

        EmailDraft::updateOrCreate(
            ['email_message_id' => $msg1->id],
            [
                'ai_analysis_id' => $analysis1->id,
                'proposed_body' => "Hi Sarah,\n\nI have reviewed Section 4 of the MSA and everything looks great. We are ready to proceed with signatures.\n\nPlease send the DocuSign link over and I'll execute it immediately.\n\nBest regards,\nJohn",
                'status' => 'pending',
            ]
        );

        AuditLog::record($user, 'email.received', $msg1->id, ['subject' => $msg1->subject]);
        AuditLog::record($user, 'email.analyzed', $msg1->id, ['urgency_score' => 4.9]);
        AuditLog::record($user, 'draft.proposed', $msg1->id);

        // 2. Important Investor Meeting Request
        $msg2 = EmailMessage::firstOrCreate(
            ['gmail_message_id' => 'msg_demo_002'],
            [
                'user_id' => $user->id,
                'gmail_thread_id' => 'thd_demo_002',
                'sender_name' => 'Marcus Vance',
                'sender_email' => 'm.vance@sequoia-cap.com',
                'recipient_email' => $user->email,
                'subject' => 'Intro & Series A Follow-up Discussion',
                'snippet' => 'Hi John, Great meeting you last week. We would love to schedule a 30-min sync next Tuesday at 2 PM...',
                'body_plain' => "Hi John,\n\nGreat meeting you at the AI Founders Dinner last week. The progress on your autonomous email agent is very impressive.\n\nWe'd love to schedule a 30-min follow-up sync next Tuesday at 2:00 PM EST to dive deeper into your retention metrics and growth roadmap.\n\nDoes that time work for you?\n\nBest,\nMarcus Vance\nPartner, Sequoia Capital",
                'has_attachments' => false,
                'received_at' => now()->subHours(2),
                'is_read' => false,
            ]
        );

        $analysis2 = AiAnalysis::updateOrCreate(
            ['email_message_id' => $msg2->id],
            [
                'model_version' => 'claude-3-5-sonnet-20241022',
                'intent' => 'Meeting Request',
                'urgency_level' => 'important',
                'urgency_score' => 3.9,
                'sentiment' => 'positive',
                'deadline_detected' => now()->addDays(3)->setTime(14, 0, 0),
                'suggested_labels' => ['Investors', 'Meetings', 'Important'],
                'summary' => 'Marcus is requesting a 30-minute Series A follow-up discussion next Tuesday at 2 PM EST regarding retention metrics.',
                'requires_reply' => true,
                'tokens_used' => 410,
                'raw_response' => ['intent' => 'Meeting Request', 'urgency_score' => 3.9],
            ]
        );

        EmailDraft::updateOrCreate(
            ['email_message_id' => $msg2->id],
            [
                'ai_analysis_id' => $analysis2->id,
                'proposed_body' => "Hi Marcus,\n\nThanks for reaching out! Tuesday at 2:00 PM EST works perfectly.\n\nI'll send a calendar invite with our Zoom link and prepare our latest cohort retention deck for the call.\n\nLooking forward to speaking.\n\nBest,\nJohn",
                'status' => 'pending',
            ]
        );

        AuditLog::record($user, 'email.received', $msg2->id, ['subject' => $msg2->subject]);
        AuditLog::record($user, 'email.analyzed', $msg2->id, ['urgency_score' => 3.9]);

        // 3. Dispatched / Sent Reply
        $msg3 = EmailMessage::firstOrCreate(
            ['gmail_message_id' => 'msg_demo_003'],
            [
                'user_id' => $user->id,
                'gmail_thread_id' => 'thd_demo_003',
                'sender_name' => 'Elena Rostova',
                'sender_email' => 'elena@cloudscale.net',
                'recipient_email' => $user->email,
                'subject' => 'API Integration & Webhook Throughput',
                'snippet' => 'Can you confirm whether the webhook throughput limits will support 50k req/min?',
                'body_plain' => "Hi John,\n\nOur engineering team is finalizing the integration architecture. Can you confirm if your webhook throughput limits will support up to 50,000 req/min during peak bursts?\n\nThanks,\nElena",
                'has_attachments' => false,
                'received_at' => now()->subHours(5),
                'is_read' => true,
            ]
        );

        $analysis3 = AiAnalysis::updateOrCreate(
            ['email_message_id' => $msg3->id],
            [
                'model_version' => 'claude-3-5-sonnet-20241022',
                'intent' => 'Technical Inquiry',
                'urgency_level' => 'important',
                'urgency_score' => 3.5,
                'sentiment' => 'neutral',
                'deadline_detected' => null,
                'suggested_labels' => ['Engineering', 'API'],
                'summary' => 'Elena inquired about webhook throughput scaling to 50k req/min during peak traffic.',
                'requires_reply' => true,
                'tokens_used' => 290,
                'raw_response' => ['intent' => 'Technical Inquiry'],
            ]
        );

        $draft3 = EmailDraft::updateOrCreate(
            ['email_message_id' => $msg3->id],
            [
                'ai_analysis_id' => $analysis3->id,
                'proposed_body' => "Hi Elena,\n\nYes, our event ingestion pipeline easily handles 100k+ req/min with sub-50ms latency. We're fully prepared for your burst volume.\n\nBest,\nJohn",
                'status' => 'sent',
                'sent_at' => now()->subHours(3),
                'gmail_sent_message_id' => 'sent_demo_elena_888',
            ]
        );

        AuditLog::record($user, 'email.received', $msg3->id);
        AuditLog::record($user, 'email.analyzed', $msg3->id);
        AuditLog::record($user, 'draft.approved', $msg3->id);
        AuditLog::record($user, 'email.sent', $msg3->id, ['sent_id' => 'sent_demo_elena_888']);

        // 4. Automated Digest (No reply required)
        $msg4 = EmailMessage::firstOrCreate(
            ['gmail_message_id' => 'msg_demo_004'],
            [
                'user_id' => $user->id,
                'gmail_thread_id' => 'thd_demo_004',
                'sender_name' => 'Stripe Radar',
                'sender_email' => 'notifications@stripe.com',
                'recipient_email' => $user->email,
                'subject' => 'Monthly Fraud Prevention & Radar Digest',
                'snippet' => 'Here is your monthly risk analysis report. 0.02% dispute rate observed...',
                'body_plain' => "Your monthly Stripe Radar digest is ready.\n\nKey Highlights:\n- Dispute Rate: 0.02% (Industry Benchmark: 0.45%)\n- Blocked Fraudulent Attempts: 14\n- Total Processed Volume: $142,500\n\nNo action is needed on your account.",
                'has_attachments' => false,
                'received_at' => now()->subDay(),
                'is_read' => true,
            ]
        );

        AiAnalysis::updateOrCreate(
            ['email_message_id' => $msg4->id],
            [
                'model_version' => 'claude-3-5-sonnet-20241022',
                'intent' => 'Notification',
                'urgency_level' => 'routine',
                'urgency_score' => 1.2,
                'sentiment' => 'positive',
                'deadline_detected' => null,
                'suggested_labels' => ['Finance', 'Reports'],
                'summary' => 'Stripe monthly security report with healthy 0.02% dispute rate. No action required.',
                'requires_reply' => false,
                'tokens_used' => 190,
                'raw_response' => ['intent' => 'Notification', 'requires_reply' => false],
            ]
        );

        AuditLog::record($user, 'email.received', $msg4->id);
        AuditLog::record($user, 'email.analyzed', $msg4->id);
    }
}
