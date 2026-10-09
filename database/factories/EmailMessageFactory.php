<?php

namespace Database\Factories;

use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EmailMessage>
 */
class EmailMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gmail_message_id' => 'msg_'.Str::random(16),
            'gmail_thread_id' => 'thd_'.Str::random(16),
            'sender_name' => fake()->name(),
            'sender_email' => fake()->safeEmail(),
            'recipient_email' => fake()->safeEmail(),
            'subject' => fake()->sentence(5),
            'snippet' => fake()->paragraph(1),
            'body_plain' => fake()->paragraphs(3, true),
            'body_html' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'has_attachments' => false,
            'received_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'is_read' => false,
        ];
    }

    /**
     * Indicate that the email has attachments.
     */
    public function withAttachments(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_attachments' => true,
        ]);
    }

    /**
     * Indicate that the email has been read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }
}
