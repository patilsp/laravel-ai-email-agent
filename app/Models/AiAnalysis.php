<?php

namespace App\Models;

use Database\Factories\AiAnalysisFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AiAnalysis extends Model
{
    /** @use HasFactory<AiAnalysisFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email_message_id',
        'model_version',
        'intent',
        'urgency_level',
        'urgency_score',
        'sentiment',
        'deadline_detected',
        'suggested_labels',
        'summary',
        'requires_reply',
        'tokens_used',
        'raw_response',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'urgency_score' => 'decimal:1',
            'deadline_detected' => 'datetime',
            'suggested_labels' => 'array',
            'requires_reply' => 'boolean',
            'tokens_used' => 'integer',
            'raw_response' => 'array',
        ];
    }

    /**
     * Get the email message that owns the AI analysis.
     */
    public function emailMessage(): BelongsTo
    {
        return $this->belongsTo(EmailMessage::class);
    }

    /**
     * Get the email draft associated with this analysis.
     */
    public function draft(): HasOne
    {
        return $this->hasOne(EmailDraft::class);
    }

    /**
     * Check if urgency level is urgent.
     */
    public function isUrgent(): bool
    {
        return $this->urgency_level === 'urgent';
    }

    /**
     * Check if urgency level is important.
     */
    public function isImportant(): bool
    {
        return $this->urgency_level === 'important';
    }

    /**
     * Scope query to urgent analyses.
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('urgency_level', 'urgent');
    }

    /**
     * Scope query to analyses requiring replies.
     */
    public function scopeRequiringReply(Builder $query): Builder
    {
        return $query->where('requires_reply', true);
    }
}
