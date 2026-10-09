<?php

namespace App\Http\Controllers;

use App\Jobs\PollGmailInboxJob;
use App\Models\User;
use App\Services\GmailApiService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class DashboardController extends Controller
{
    /**
     * Show the application workspace dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $isConnected = $user->isConnectedToGoogle();
        $googleToken = $user->googleToken;

        // User stats
        $totalEmails = $user->emailMessages()->count();
        $pendingDraftsCount = $user->emailMessages()
            ->whereHas('draft', fn (Builder $q) => $q->whereIn('status', ['pending', 'edited']))
            ->count();
        $urgentEmailsCount = $user->emailMessages()
            ->whereHas('aiAnalysis', fn (Builder $q) => $q->where('urgency_level', 'urgent'))
            ->count();
        $sentRepliesCount = $user->emailMessages()
            ->whereHas('draft', fn (Builder $q) => $q->where('status', 'sent'))
            ->count();

        // Build query for emails
        $query = $user->emailMessages()
            ->with(['aiAnalysis', 'draft', 'auditLogs' => fn ($q) => $q->latest()->limit(5)])
            ->latest('received_at');

        // Apply search keyword
        $search = trim((string) $request->query('q', ''));
        if (! empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('sender_name', 'like', "%{$search}%")
                    ->orWhere('sender_email', 'like', "%{$search}%")
                    ->orWhere('snippet', 'like', "%{$search}%")
                    ->orWhere('body_plain', 'like', "%{$search}%");
            });
        }

        // Apply filter
        $filter = (string) $request->query('filter', 'all');
        match ($filter) {
            'urgent' => $query->whereHas('aiAnalysis', fn (Builder $q) => $q->where('urgency_level', 'urgent')),
            'important' => $query->whereHas('aiAnalysis', fn (Builder $q) => $q->where('urgency_level', 'important')),
            'pending' => $query->whereHas('draft', fn (Builder $q) => $q->whereIn('status', ['pending', 'edited'])),
            'sent' => $query->whereHas('draft', fn (Builder $q) => $q->where('status', 'sent')),
            default => null,
        };

        $emails = $query->paginate(25)->withQueryString();

        // Determine currently selected email
        $selectedId = $request->query('selected');
        $selectedEmail = null;

        if ($selectedId) {
            $selectedEmail = $user->emailMessages()
                ->with(['aiAnalysis', 'draft', 'auditLogs' => fn ($q) => $q->latest()->limit(10)])
                ->find($selectedId);
        }

        if (! $selectedEmail && $emails->isNotEmpty()) {
            $selectedEmail = $emails->first();
        }

        return view('dashboard', [
            'user' => $user,
            'isConnected' => $isConnected,
            'googleToken' => $googleToken,
            'totalEmails' => $totalEmails,
            'pendingDraftsCount' => $pendingDraftsCount,
            'urgentEmailsCount' => $urgentEmailsCount,
            'sentRepliesCount' => $sentRepliesCount,
            'emails' => $emails,
            'selectedEmail' => $selectedEmail,
            'currentFilter' => $filter,
            'searchQuery' => $search,
        ]);
    }

    /**
     * Trigger immediate inbox synchronization.
     */
    public function sync(Request $request, GmailApiService $gmailService): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->isConnectedToGoogle()) {
            return redirect()->route('dashboard')->with('error', 'Please connect your Google account before syncing.');
        }

        try {
            $job = new PollGmailInboxJob($user);
            $job->handle($gmailService);

            return redirect()->route('dashboard')->with('status', 'Inbox sync completed successfully. New emails analyzed.');
        } catch (Throwable $e) {
            return redirect()->route('dashboard')->with('error', 'Inbox sync failed: '.$e->getMessage());
        }
    }
}
