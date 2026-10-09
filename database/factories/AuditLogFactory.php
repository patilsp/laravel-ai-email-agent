<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
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
            'email_message_id' => EmailMessage::factory(),
            'event_type' => fake()->randomElement([
                'email.received',
                'email.analyzed',
                'draft.proposed',
                'draft.approved',
                'draft.edited',
                'draft.rejected',
                'email.sent',
            ]),
            'metadata' => [
                'ip' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ],
            'created_at' => now(),
        ];
    }
}
