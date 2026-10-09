<?php

namespace Tests\Unit;

use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OAuthTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_encrypts_access_and_refresh_tokens_in_database(): void
    {
        $rawAccessToken = 'ya29.secret_access_token_12345';
        $rawRefreshToken = '1//secret_refresh_token_67890';

        $user = User::factory()->create();

        $token = OAuthToken::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'access_token' => $rawAccessToken,
            'refresh_token' => $rawRefreshToken,
            'expires_at' => now()->addHour(),
            'scopes' => ['https://www.googleapis.com/auth/gmail.modify'],
        ]);

        // Verify model decrypts values on retrieval
        $this->assertEquals($rawAccessToken, $token->access_token);
        $this->assertEquals($rawRefreshToken, $token->refresh_token);

        // Inspect raw database record directly to ensure it is encrypted at rest
        $rawDbRecord = DB::table('oauth_tokens')->where('id', $token->id)->first();
        $this->assertNotEquals($rawAccessToken, $rawDbRecord->access_token);
        $this->assertNotEquals($rawRefreshToken, $rawDbRecord->refresh_token);
    }

    public function test_is_expired_correctly_evaluates_token_expiration(): void
    {
        $user = User::factory()->create();

        // Valid future token (2 hours from now)
        $validToken = OAuthToken::factory()->create([
            'user_id' => $user->id,
            'expires_at' => now()->addHours(2),
        ]);
        $this->assertFalse($validToken->isExpired());

        // Expired token (1 hour ago)
        $expiredToken = OAuthToken::factory()->expired()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $this->assertTrue($expiredToken->isExpired());

        // Token expiring in 30 seconds (within grace window)
        $aboutToExpireToken = OAuthToken::factory()->create([
            'user_id' => User::factory()->create()->id,
            'expires_at' => now()->addSeconds(30),
        ]);
        $this->assertTrue($aboutToExpireToken->isExpired());
    }

    public function test_has_scope_identifies_matching_oauth_scopes(): void
    {
        $token = OAuthToken::factory()->create([
            'scopes' => [
                'openid',
                'https://www.googleapis.com/auth/gmail.modify',
            ],
        ]);

        $this->assertTrue($token->hasScope('https://www.googleapis.com/auth/gmail.modify'));
        $this->assertTrue($token->hasScope('openid'));
        $this->assertFalse($token->hasScope('https://www.googleapis.com/auth/calendar'));
    }

    public function test_user_relationship_links_correctly(): void
    {
        $user = User::factory()->create();
        $token = OAuthToken::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($token->user->is($user));
        $this->assertTrue($user->googleToken->is($token));
        $this->assertTrue($user->isConnectedToGoogle());
    }
}
