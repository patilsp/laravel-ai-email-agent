<?php

namespace Database\Factories;

use App\Models\AiAnalysis;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EmailDraft>
 */
class EmailDraftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email_message_id' => EmailMessage::factory(),
            'ai_analysis_id' => AiAnalysis::factory(),
            'proposed_body' => fake()->paragraphs(2, true),
            'edited_body' => null,
            'status' => 'pending',
            'sent_at' => null,
            'gmail_sent_message_id' => null,
        ];
    }

    /**
     * Indicate that the draft has been approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the draft was edited by human.
     */
    public function edited(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'edited',
            'edited_body' => fake()->paragraphs(2, true),
        ]);
    }

    /**
     * Indicate that the draft was rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the draft was dispatched / sent.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => now(),
            'gmail_sent_message_id' => 'sent_'.Str::random(16),
        ]);
    }
}
