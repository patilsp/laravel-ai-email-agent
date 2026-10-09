<?php

namespace Database\Factories;

use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OAuthToken>
 */
class OAuthTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<OAuthToken>
     */
    protected $model = OAuthToken::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider' => 'google',
            'access_token' => 'ya29.sample_mock_access_token_'.fake()->uuid(),
            'refresh_token' => '1//sample_mock_refresh_token_'.fake()->uuid(),
            'expires_at' => now()->addHour(),
            'scopes' => [
                'openid',
                'profile',
                'email',
                'https://www.googleapis.com/auth/gmail.modify',
                'https://www.googleapis.com/auth/gmail.compose',
                'https://www.googleapis.com/auth/gmail.labels',
            ],
        ];
    }

    /**
     * Indicate that the token is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subHour(),
        ]);
    }
}
