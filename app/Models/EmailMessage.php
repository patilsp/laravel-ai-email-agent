<?php

namespace App\Models;

use Database\Factories\EmailMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmailMessage extends Model
{
    /** @use HasFactory<EmailMessageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'gmail_message_id',
        'gmail_thread_id',
        'sender_name',
        'sender_email',
        'recipient_email',
        'subject',
        'snippet',
        'body_plain',
        'body_html',
        'has_attachments',
        'received_at',
        'is_read',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_attachments' => 'boolean',
            'is_read' => 'boolean',
            'received_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the email message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI analysis associated with the email message.
     */
    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class);
    }

    /**
     * Get the email draft associated with the email message.
     */
    public function draft(): HasOne
    {
        return $this->hasOne(EmailDraft::class);
    }

    /**
     * Get the audit logs for this email message.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Scope a query to only include unread emails.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include urgent emails.
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->whereHas('aiAnalysis', function (Builder $q) {
            $q->where('urgency_level', 'urgent');
        });
    }

    /**
     * Get sender display name or email if name is missing.
     */
    public function getSenderDisplayAttribute(): string
    {
        return $this->sender_name ?: $this->sender_email;
    }

    /**
     * Determine if email has been analyzed by AI.
     */
    public function isAnalyzed(): bool
    {
        return $this->aiAnalysis()->exists();
    }
}
