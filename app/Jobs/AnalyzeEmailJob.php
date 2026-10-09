<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\EmailMessage;
use App\Services\EmailAnalyzerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var list<int>
     */
    public array $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public EmailMessage $emailMessage,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(EmailAnalyzerService $analyzerService): void
    {
        $analyzerService->analyze($this->emailMessage);
    }

    /**
     * Handle a job failure after all retries exhausted.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('AnalyzeEmailJob permanently failed for email #'.$this->emailMessage->id, [
            'email_message_id' => $this->emailMessage->id,
            'error' => $exception?->getMessage(),
        ]);

        AuditLog::record(
            user: $this->emailMessage->user_id,
            eventType: 'email.analysis_exhausted',
            emailMessageId: $this->emailMessage->id,
            metadata: [
                'error' => $exception?->getMessage(),
                'attempts' => $this->attempts(),
            ]
        );
    }
}
