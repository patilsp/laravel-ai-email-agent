<?php

namespace App\Models;

use Database\Factories\AuditLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /** @use HasFactory<AuditLogFactory> */
    use HasFactory;

    /**
     * Disable updated_at since audit logs are append-only.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'email_message_id',
        'event_type',
        'metadata',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user associated with the audit event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the email message associated with the audit event.
     */
    public function emailMessage(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class);
    }

    /**
     * Helper to record a new audit log event.
     *
     * @param  array<string, mixed>  $metadata
     */
    public static function record(User|int $user, string $eventType, ?int $emailMessageId = null, array $metadata = []): self
    {
        $userId = $user instanceof User ? $user->id : $user;

        return static::create([
            'user_id' => $userId,
            'email_message_id' => $emailMessageId,
            'event_type' => $eventType,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
