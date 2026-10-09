<?php

namespace App\Services;

use App\Models\OAuthToken;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTokenService
{
    /**
     * Get a valid, non-expired Google access token for the given user.
     * Automatically refreshes the token if expired.
     */
    public function getValidAccessToken(User $user): ?string
    {
        $token = $user->googleToken;

        if (! $token) {
            return null;
        }

        if (! $token->isExpired()) {
            return $token->access_token;
        }

        $refreshed = $this->refreshToken($token);

        return $refreshed ? $token->fresh()->access_token : null;
    }

    /**
     * Refresh the OAuth token using Google's OAuth2 token endpoint.
     */
    public function refreshToken(OAuthToken $token): bool
    {
        if (empty($token->refresh_token)) {
            Log::warning("Cannot refresh Google OAuth token for user {$token->user_id}: Missing refresh token.");

            return false;
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $token->refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $expiresIn = (int) ($data['expires_in'] ?? 3600);

                $token->update([
                    'access_token' => $data['access_token'],
                    'expires_at' => now()->addSeconds($expiresIn),
                ]);

                return true;
            }

            Log::error("Failed to refresh Google OAuth token for user {$token->user_id}", [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error("Exception occurred while refreshing Google OAuth token for user {$token->user_id}: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Revoke the token with Google and remove it from the database.
     */
    public function revokeToken(OAuthToken $token): bool
    {
        try {
            if (! empty($token->access_token)) {
                Http::asForm()->post('https://oauth2.googleapis.com/revoke', [
                    'token' => $token->access_token,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning("Could not revoke token directly on Google servers: {$e->getMessage()}");
        }

        return (bool) $token->delete();
    }
}
