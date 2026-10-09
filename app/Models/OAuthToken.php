<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthToken extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'access_token',
        'refresh_token',
        'expires_at',
        'scopes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'expires_at' => 'datetime',
            'scopes' => 'array',
        ];
    }

    /**
     * Get the user that owns the OAuth token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine if the access token is expired or about to expire in 60 seconds.
     */
    public function isExpired(): bool
    {
        if (! $this->expires_at) {
            return false;
        }

        return $this->expires_at->subSeconds(60)->isPast();
    }

    /**
     * Determine if the token possesses a specific OAuth scope.
     */
    public function hasScope(string $scope): bool
    {
        if (! is_array($this->scopes)) {
            return false;
        }

        return in_array($scope, $this->scopes, true);
    }
}
