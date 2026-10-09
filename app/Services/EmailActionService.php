<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\EmailDraft;
use App\Models\EmailMessage;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class EmailActionService
{
    public function __construct(
        protected GmailApiService $gmailService,
    ) {}

    /**
     * Approve a proposed or edited draft and send it via Gmail API.
     *
     * @return array<string, mixed>
     *
     * @throws AuthorizationException|Throwable
     */
    public function approveAndSendDraft(User $user, EmailDraft $draft): array
    {
        $emailMessage = $draft->emailMessage;

        if ($emailMessage->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to send this draft.');
        }

        $subject = $emailMessage->subject ?: 'Response';
        $replySubject = str_starts_with(strtolower($subject), 're:') ? $subject : 'Re: '.$subject;

        $result = $this->gmailService->sendMessage(
            user: $user,
            to: $emailMessage->sender_email,
            subject: $replySubject,
            body: $draft->effective_body,
            threadId: $emailMessage->gmail_thread_id,
            inReplyTo: $emailMessage->gmail_message_id,
        );

        $sentMessageId = (string) ($result['id'] ?? 'sent_'.str()->random(16));
        $draft->markSent($sentMessageId);

        AuditLog::record(
            user: $user,
            eventType: 'email.sent',
            emailMessageId: $emailMessage->id,
            metadata: [
                'draft_id' => $draft->id,
                'sent_message_id' => $sentMessageId,
                'recipient' => $emailMessage->sender_email,
                'subject' => $replySubject,
            ]
        );

        return $result;
    }

    /**
     * Edit a proposed draft body.
     *
     * @throws AuthorizationException
     */
    public function editDraft(User $user, EmailDraft $draft, string $newBody): EmailDraft
    {
        if ($draft->emailMessage->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to edit this draft.');
        }

        $draft->markEdited($newBody);

        AuditLog::record(
            user: $user,
            eventType: 'draft.edited',
            emailMessageId: $draft->email_message_id,
            metadata: [
                'draft_id' => $draft->id,
            ]
        );

        return $draft->fresh();
    }

    /**
     * Reject a proposed draft.
     *
     * @throws AuthorizationException
     */
    public function rejectDraft(User $user, EmailDraft $draft): EmailDraft
    {
        if ($draft->emailMessage->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to reject this draft.');
        }

        $draft->markRejected();

        AuditLog::record(
            user: $user,
            eventType: 'draft.rejected',
            emailMessageId: $draft->email_message_id,
            metadata: [
                'draft_id' => $draft->id,
            ]
        );

        return $draft->fresh();
    }

    /**
     * Apply AI suggested labels to the message in Gmail.
     *
     * @return array<string, mixed>
     *
     * @throws AuthorizationException
     */
    public function applySuggestedLabels(User $user, EmailMessage $emailMessage): array
    {
        if ($emailMessage->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to modify this email.');
        }

        $suggestedLabels = $emailMessage->aiAnalysis?->suggested_labels ?? [];
        $appliedLabelIds = [];

        foreach ($suggestedLabels as $labelName) {
            $labelId = $this->gmailService->getOrCreateLabel($user, (string) $labelName);
            if ($labelId) {
                $appliedLabelIds[] = $labelId;
            }
        }

        if (! empty($appliedLabelIds)) {
            $this->gmailService->applyLabels($user, $emailMessage->gmail_message_id, $appliedLabelIds);
        }

        AuditLog::record(
            user: $user,
            eventType: 'labels.applied',
            emailMessageId: $emailMessage->id,
            metadata: [
                'labels' => $suggestedLabels,
                'label_ids' => $appliedLabelIds,
            ]
        );

        return [
            'success' => true,
            'applied_labels' => $suggestedLabels,
            'label_ids' => $appliedLabelIds,
        ];
    }

    /**
     * Move an email message to Trash in Gmail and delete local record.
     *
     * @throws AuthorizationException
     */
    public function trashEmail(User $user, EmailMessage $emailMessage): bool
    {
        if ($emailMessage->user_id !== $user->id) {
            throw new AuthorizationException('You are not authorized to delete this email.');
        }

        $success = $this->gmailService->trashMessage($user, $emailMessage->gmail_message_id);

        AuditLog::record(
            user: $user,
            eventType: 'email.trashed',
            emailMessageId: $emailMessage->id,
            metadata: [
                'gmail_message_id' => $emailMessage->gmail_message_id,
                'subject' => $emailMessage->subject,
            ]
        );

        $emailMessage->delete();

        return $success;
    }

    /**
     * Compose and send a new custom email.
     *
     * @return array<string, mixed>
     */
    public function sendCustomEmail(User $user, string $to, string $subject, string $body): array
    {
        $result = $this->gmailService->sendMessage(
            user: $user,
            to: $to,
            subject: $subject,
            body: $body
        );

        AuditLog::record(
            user: $user,
            eventType: 'email.composed_and_sent',
            emailMessageId: null,
            metadata: [
                'to' => $to,
                'subject' => $subject,
                'sent_id' => $result['id'] ?? null,
            ]
        );

        return $result;
    }
}
