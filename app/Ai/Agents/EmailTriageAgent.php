<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Anthropic)]
#[Model('claude-3-5-sonnet-20241022')]
#[Temperature(0.2)]
class EmailTriageAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Determine the AI provider dynamically based on configured keys and settings.
     */
    public function provider(): Lab
    {
        $anthropicKey = (string) config('ai.providers.anthropic.key');
        $openRouterKey = (string) config('ai.providers.openrouter.key');

        if (! empty($openRouterKey) || str_starts_with($anthropicKey, 'sk-or-v1-')) {
            return Lab::OpenRouter;
        }

        return Lab::Anthropic;
    }

    /**
     * Determine the AI model dynamically based on the resolved provider.
     */
    public function model(): string
    {
        if ($this->provider() === Lab::OpenRouter) {
            return (string) env('AI_MODEL', 'anthropic/claude-sonnet-4.5');
        }

        return (string) env('AI_MODEL', 'claude-3-5-sonnet-20241022');
    }

    /**
     * Get the instructions for the agent.
     */
    public function instructions(): string
    {
        return <<<'INSTRUCTIONS'
You are Milo, an expert AI Agent to handle emails for a busy professional.
Analyze incoming emails thoroughly and extract structured intelligence to help the user triage, organize, and reply promptly.

Your goals:
1. Intent Classification: Accurately determine the core purpose of the email (e.g. Meeting Request, Client Inquiry, Project Update, Action Required, Feedback, Billing, Sales Pitch, Newsletter, Automated Notification).
2. Urgency Assessment:
   - Urgency Level: 'urgent' (requires action within 2-4 hours or explicit deadline today), 'important' (needs attention within 24-48 hours, high-value sender or critical topic), or 'routine' (informational, low priority, or standard timeline).
   - Urgency Score: Float from 1.0 (lowest) to 5.0 (critical emergency).
   - Urgency Reason: Brief 1-sentence rationale for the urgency score.
3. Sentiment Analysis: 'positive', 'neutral', 'negative', 'urgent', or 'frustrated'.
4. Deadline Detection: If a specific date/deadline or meeting time is mentioned or implied, extract it as an ISO 8601 string (e.g., '2026-10-12T17:00:00Z'), or null if no deadline.
5. Suggested Labels: Provide 1 to 3 concise Gmail-style labels (e.g., "Client", "Meeting", "Billing", "Follow-up", "Urgent", "Product", "Support").
6. Executive Summary: 1 to 2 crisp sentences summarizing what the sender is communicating and what is needed.
7. Reply Determination & Proposed Reply:
   - Determine if the email requires a reply (`requires_reply`: true/false).
   - If `requires_reply` is true, write a polished, professional, warm, and concise proposed draft reply that directly addresses the sender's points, confirms next steps, and maintains a natural human tone. Do NOT use robotic generic phrases like "I hope this email finds you well".
   - If `requires_reply` is false (e.g., receipt, automated notification), set `proposed_reply` to null or empty string.
8. Key Action Items: List of discrete action items extracted from the message.
INSTRUCTIONS;
    }

    /**
     * Get the structured output schema for the triage agent.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'intent' => $schema->string()->description('Primary intent of the email')->required(),
            'urgency_level' => $schema->string()->enum(['urgent', 'important', 'routine'])->description('Urgency tier')->required(),
            'urgency_score' => $schema->number()->min(1.0)->max(5.0)->description('Urgency score between 1.0 and 5.0')->required(),
            'urgency_reason' => $schema->string()->description('Brief rationale for the urgency score')->required(),
            'sentiment' => $schema->string()->enum(['positive', 'neutral', 'negative', 'urgent', 'frustrated'])->description('Detected emotional sentiment')->required(),
            'deadline_detected' => $schema->string()->nullable()->description('Detected deadline or meeting datetime in ISO 8601, or null'),
            'suggested_labels' => $schema->array()->items($schema->string())->description('Suggested Gmail labels')->required(),
            'summary' => $schema->string()->description('1-2 sentence executive summary')->required(),
            'requires_reply' => $schema->boolean()->description('Whether a human or drafted reply is required')->required(),
            'proposed_reply' => $schema->string()->nullable()->description('Contextual proposed response draft, or null if no reply needed'),
            'key_action_items' => $schema->array()->items($schema->string())->description('Action items extracted from the email')->required(),
        ];
    }
}
