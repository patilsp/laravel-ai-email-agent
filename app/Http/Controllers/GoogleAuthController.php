<?php

namespace App\Http\Controllers;

use App\Models\OAuthToken;
use App\Models\User;
use App\Services\GoogleTokenService;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle manual email login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user && $credentials['email'] === 'demo@ai-email-agent.local') {
            $user = User::create([
                'name' => 'Executive Demo User',
                'email' => 'demo@ai-email-agent.local',
                'password' => bcrypt($credentials['password']),
            ]);
            DemoDataSeeder::seedUser($user);
        }

        if ($user && (Hash::check($credentials['password'], $user->password) || $credentials['email'] === 'demo@ai-email-agent.local')) {
            Auth::login($user, remember: true);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Redirect the user to the Google OAuth consent page.
     */
    public function redirect(): Response
    {
        $scopes = config('services.google.scopes', [
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/gmail.modify',
            'https://www.googleapis.com/auth/gmail.compose',
            'https://www.googleapis.com/auth/gmail.labels',
        ]);

        return Socialite::driver('google')
            ->scopes($scopes)
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent select_account',
            ])
            ->redirect();
    }

    /**
     * Handle the OAuth callback from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::error("Google OAuth authentication failed: {$e->getMessage()}");

            return redirect()->route('home')->with('error', 'Google authentication was cancelled or failed. Please try again.');
        }

        if (empty($googleUser->getEmail())) {
            return redirect()->route('home')->with('error', 'No email address was provided by Google.');
        }

        // Find or create local user
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?? explode('@', $googleUser->getEmail())[0],
                'password' => bcrypt(str()->random(32)),
            ]
        );

        // Retrieve existing token to preserve refresh_token if not sent in this flow
        $existingToken = $user->googleToken;
        $refreshToken = $googleUser->refreshToken ?? $existingToken?->refresh_token;

        $expiresIn = (int) ($googleUser->expiresIn ?? 3600);

        OAuthToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => 'google',
            ],
            [
                'access_token' => $googleUser->token,
                'refresh_token' => $refreshToken,
                'expires_at' => now()->addSeconds($expiresIn),
                'scopes' => config('services.google.scopes', []),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->route('dashboard')->with('status', 'Successfully connected your Gmail account.');
    }

    /**
     * Disconnect and revoke the Google OAuth access.
     */
    public function disconnect(Request $request, GoogleTokenService $tokenService): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user && $user->googleToken) {
            $tokenService->revokeToken($user->googleToken);
        }

        return redirect()->back()->with('status', 'Disconnected Gmail account and removed cached tokens.');
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
