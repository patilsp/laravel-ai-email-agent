<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all OAuth tokens for the user.
     */
    public function oauthTokens(): HasMany
    {
        return $this->hasMany(OAuthToken::class);
    }

    /**
     * Get the Google OAuth token for the user.
     */
    public function googleToken(): HasOne
    {
        return $this->hasOne(OAuthToken::class)->where('provider', 'google');
    }

    /**
     * Determine if the user has an active Google OAuth connection.
     */
    public function isConnectedToGoogle(): bool
    {
        return $this->googleToken()->exists();
    }

    /**
     * Get all synced email messages for the user.
     */
    public function emailMessages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    /**
     * Get all audit logs for the user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
