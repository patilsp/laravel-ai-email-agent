<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditDraftRequest;
use App\Http\Requests\SendCustomEmailRequest;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use App\Services\EmailActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailActionController extends Controller
{
    /**
     * Approve and send a drafted reply via Gmail.
     */
    public function approve(Request $request, EmailDraft $draft, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $result = $actionService->approveAndSendDraft($request->user(), $draft);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email reply sent successfully.',
                'draft' => $draft->fresh(),
                'result' => $result,
            ]);
        }

        return redirect()->back()->with('status', 'Email reply approved and sent successfully.');
    }

    /**
     * Update/edit a draft body.
     */
    public function edit(EditDraftRequest $request, EmailDraft $draft, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $updatedDraft = $actionService->editDraft($request->user(), $draft, $request->validated('body'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Draft updated successfully.',
                'draft' => $updatedDraft,
            ]);
        }

        return redirect()->back()->with('status', 'Draft updated successfully.');
    }

    /**
     * Reject a proposed draft.
     */
    public function reject(Request $request, EmailDraft $draft, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $rejectedDraft = $actionService->rejectDraft($request->user(), $draft);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Draft rejected.',
                'draft' => $rejectedDraft,
            ]);
        }

        return redirect()->back()->with('status', 'Draft rejected.');
    }

    /**
     * Apply AI suggested labels to the Gmail thread.
     */
    public function applyLabels(Request $request, EmailMessage $emailMessage, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $result = $actionService->applySuggestedLabels($request->user(), $emailMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Suggested labels applied to Gmail thread.',
                'data' => $result,
            ]);
        }

        return redirect()->back()->with('status', 'Suggested labels applied to Gmail thread.');
    }

    /**
     * Move an email message to Trash and remove from inbox.
     */
    public function trash(Request $request, EmailMessage $emailMessage, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $actionService->trashEmail($request->user(), $emailMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email moved to Trash.',
            ]);
        }

        return redirect()->back()->with('status', 'Email moved to Trash.');
    }

    /**
     * Compose and send a custom email.
     */
    public function sendCustom(SendCustomEmailRequest $request, EmailActionService $actionService): JsonResponse|RedirectResponse
    {
        $result = $actionService->sendCustomEmail(
            user: $request->user(),
            to: $request->validated('to'),
            subject: $request->validated('subject'),
            body: $request->validated('body'),
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Custom email sent successfully.',
                'result' => $result,
            ]);
        }

        return redirect()->back()->with('status', 'Custom email sent successfully.');
    }
}
