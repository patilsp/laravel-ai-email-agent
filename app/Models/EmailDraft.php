<?php

namespace App\Models;

use Database\Factories\EmailDraftFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailDraft extends Model
{
    /** @use HasFactory<EmailDraftFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email_message_id',
        'ai_analysis_id',
        'proposed_body',
        'edited_body',
        'status',
        'sent_at',
        'gmail_sent_message_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Get the email message that owns the draft.
     */
    public function emailMessage(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class);
    }

    /**
     * Get the AI analysis that generated this draft.
     */
    public function aiAnalysis(): BelongsTo
    {
        return $this->belongsTo(AiAnalysis::class);
    }

    /**
     * Get the final effective body (edited version if available, otherwise proposed).
     */
    public function getEffectiveBodyAttribute(): string
    {
        return $this->edited_body ?? $this->proposed_body;
    }

    /**
     * Mark the draft as approved.
     */
    public function markApproved(): bool
    {
        return $this->update(['status' => 'approved']);
    }

    /**
     * Update the draft body and set status to edited.
     */
    public function markEdited(string $newBody): bool
    {
        return $this->update([
            'edited_body' => $newBody,
            'status' => 'edited',
        ]);
    }

    /**
     * Mark the draft as rejected.
     */
    public function markRejected(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Mark the draft as sent with timestamp and Gmail message ID.
     */
    public function markSent(string $gmailSentMessageId): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'gmail_sent_message_id' => $gmailSentMessageId,
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isEdited(): bool
    {
        return $this->status === 'edited';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Scope query to pending drafts.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope query to actionable drafts (pending, approved, or edited).
     */
    public function scopeActionable(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'approved', 'edited']);
    }
}
