<?php

namespace Tests\Unit;

use App\Models\OAuthToken;
use App\Models\User;
use App\Services\GoogleTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleTokenServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_valid_access_token_returns_existing_token_when_not_expired(): void
    {
        $user = User::factory()->create();
        $token = OAuthToken::factory()->create([
            'user_id' => $user->id,
            'access_token' => 'ya29.current_valid_token',
            'expires_at' => now()->addHour(),
        ]);

        $service = new GoogleTokenService;
        $validToken = $service->getValidAccessToken($user);

        $this->assertEquals('ya29.current_valid_token', $validToken);
    }

    public function test_get_valid_access_token_refreshes_token_when_expired(): void
    {
        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'ya29.newly_refreshed_token',
                'expires_in' => 3600,
                'token_type' => 'Bearer',
            ], 200),
        ]);

        $user = User::factory()->create();
        $token = OAuthToken::factory()->expired()->create([
            'user_id' => $user->id,
            'access_token' => 'ya29.old_expired_token',
            'refresh_token' => '1//valid_refresh_token',
        ]);

        $service = new GoogleTokenService;
        $validToken = $service->getValidAccessToken($user);

        $this->assertEquals('ya29.newly_refreshed_token', $validToken);
        $this->assertEquals('ya29.newly_refreshed_token', $token->fresh()->access_token);
        $this->assertFalse($token->fresh()->isExpired());
    }

    public function test_revoke_token_removes_token_from_database(): void
    {
        Http::fake([
            'https://oauth2.googleapis.com/revoke' => Http::response([], 200),
        ]);

        $user = User::factory()->create();
        $token = OAuthToken::factory()->create(['user_id' => $user->id]);

        $service = new GoogleTokenService;
        $revoked = $service->revokeToken($token);

        $this->assertTrue($revoked);
        $this->assertDatabaseMissing('oauth_tokens', ['id' => $token->id]);
    }
}
