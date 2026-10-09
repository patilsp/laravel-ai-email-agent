<?php

namespace Database\Factories;

use App\Models\AiAnalysis;
use App\Models\EmailMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiAnalysis>
 */
class AiAnalysisFactory extends Factory
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
            'model_version' => 'claude-3-5-sonnet',
            'intent' => fake()->randomElement(['Inquiry', 'Feedback', 'Meeting Request', 'Billing Query', 'Technical Support']),
            'urgency_level' => fake()->randomElement(['urgent', 'important', 'routine']),
            'urgency_score' => fake()->randomFloat(1, 1, 5),
            'sentiment' => fake()->randomElement(['positive', 'neutral', 'negative']),
            'deadline_detected' => fake()->optional()->dateTimeBetween('+1 day', '+7 days'),
            'suggested_labels' => ['INBOX', 'AI_PROCESSED', fake()->randomElement(['Client', 'Urgent', 'Inquiries'])],
            'summary' => fake()->paragraph(),
            'requires_reply' => true,
            'tokens_used' => fake()->numberBetween(150, 600),
            'raw_response' => [
                'intent' => 'Meeting Request',
                'urgency_score' => 4.5,
            ],
        ];
    }

    /**
     * Indicate that the analysis is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'urgency_level' => 'urgent',
            'urgency_score' => 5.0,
            'deadline_detected' => now()->addDay(),
        ]);
    }

    /**
     * Indicate that the analysis does not require a reply.
     */
    public function noReplyRequired(): static
    {
        return $this->state(fn (array $attributes) => [
            'requires_reply' => false,
            'urgency_level' => 'routine',
            'urgency_score' => 1.0,
        ]);
    }
}
