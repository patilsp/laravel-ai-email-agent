<?php

namespace App\Console\Commands;

use App\Jobs\PollGmailInboxJob;
use App\Models\User;
use Illuminate\Console\Command;

class SyncGmailInboxCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sync {--user= : Specific User ID to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll unread emails from Gmail and trigger AI triage analysis for connected accounts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = $this->option('user');

        if ($userId) {
            $user = User::find($userId);

            if (! $user) {
                $this->error("User #{$userId} not found.");

                return self::FAILURE;
            }

            if (! $user->isConnectedToGoogle()) {
                $this->warn("User #{$userId} ({$user->email}) does not have an active Google connection.");

                return self::FAILURE;
            }

            PollGmailInboxJob::dispatch($user);
            $this->info("Dispatched Gmail sync job for user #{$user->id} ({$user->email}).");

            return self::SUCCESS;
        }

        $connectedUsers = User::whereHas('oauthTokens', function ($query) {
            $query->where('provider', 'google');
        })->get();

        if ($connectedUsers->isEmpty()) {
            $this->info('No connected Google accounts found to sync.');

            return self::SUCCESS;
        }

        foreach ($connectedUsers as $user) {
            PollGmailInboxJob::dispatch($user);
            $this->line("Dispatched inbox sync for user #{$user->id} ({$user->email}).");
        }

        $this->info("Successfully scheduled inbox sync for {$connectedUsers->count()} connected user(s).");

        return self::SUCCESS;
    }
}
