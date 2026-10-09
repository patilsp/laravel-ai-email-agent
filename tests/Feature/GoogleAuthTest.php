<?php

namespace Tests\Feature;

use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_route_redirects_to_google_consent(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_creates_user_and_stores_encrypted_token(): void
    {
        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-unique-id-12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('alex@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('Alex Mercer');
        $abstractUser->token = 'ya29.sample_mock_oauth_access_token';
        $abstractUser->refreshToken = '1//sample_mock_refresh_token';
        $abstractUser->expiresIn = 3600;

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'alex@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Alex Mercer', $user->name);

        $token = $user->googleToken;
        $this->assertNotNull($token);
        $this->assertEquals('ya29.sample_mock_oauth_access_token', $token->access_token);
        $this->assertEquals('1//sample_mock_refresh_token', $token->refresh_token);
        $this->assertFalse($token->isExpired());
    }

    public function test_disconnect_route_removes_token_and_keeps_user_logged_in(): void
    {
        $user = User::factory()->create();
        OAuthToken::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->isConnectedToGoogle());

        $response = $this->actingAs($user)->post(route('auth.google.disconnect'));

        $response->assertStatus(302);
        $this->assertFalse($user->fresh()->isConnectedToGoogle());
        $this->assertDatabaseMissing('oauth_tokens', ['user_id' => $user->id]);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Sign in to Milo');
        $response->assertSee('Instant Demo Sandbox');
    }

    public function test_user_can_login_with_email_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'executive@company.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'executive@company.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_dashboard_displays_connection_status_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        OAuthToken::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Gmail Connected');
        $response->assertSee($user->email);
    }
}
