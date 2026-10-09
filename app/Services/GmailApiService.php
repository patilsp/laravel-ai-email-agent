<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GmailApiService
{
    protected const BASE_URL = 'https://gmail.googleapis.com/gmail/v1/users/me';

    public function __construct(
        protected GoogleTokenService $tokenService,
    ) {}

    /**
     * Get an authenticated HTTP client for the given user.
     *
     * @throws RuntimeException
     */
    protected function getClient(User $user): PendingRequest
    {
        $token = $this->tokenService->getValidAccessToken($user);

        if (! $token) {
            throw new RuntimeException("User #{$user->id} does not have a valid Google access token.");
        }

        return Http::withToken($token)
            ->baseUrl(self::BASE_URL)
            ->timeout(30)
            ->retry(2, 500);
    }

    /**
     * List message summaries matching a query filter.
     *
     * @return array<int, array{id: string, threadId: string}>
     */
    public function listMessages(User $user, string $query = 'is:unread -label:AI_PROCESSED', int $maxResults = 20): array
    {
        $response = $this->getClient($user)->get('/messages', [
            'q' => $query,
            'maxResults' => $maxResults,
        ]);

        if (! $response->successful()) {
            $errorJson = $response->json('error');
            $msg = is_array($errorJson) ? ($errorJson['message'] ?? $response->body()) : $response->body();
            Log::error("Gmail API listMessages failed for user #{$user->id}: {$msg}");

            throw new RuntimeException($msg);
        }

        return (array) ($response->json('messages') ?? []);
    }

    /**
     * Retrieve full details of a specific message.
     *
     * @return array<string, mixed>
     *
     * @throws RuntimeException
     */
    public function getMessage(User $user, string $messageId, string $format = 'full'): array
    {
        $response = $this->getClient($user)->get("/messages/{$messageId}", [
            'format' => $format,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException("Failed to fetch Gmail message #{$messageId}: ".$response->body());
        }

        return (array) $response->json();
    }

    /**
     * Parse raw Gmail message payload into standard normalized array.
     *
     * @param  array<string, mixed>  $rawMessage
     * @return array<string, mixed>
     */
    public function parseMessagePayload(array $rawMessage): array
    {
        $headers = collect($rawMessage['payload']['headers'] ?? []);

        $headerMap = [];
        foreach ($headers as $h) {
            $headerMap[strtolower((string) ($h['name'] ?? ''))] = (string) ($h['value'] ?? '');
        }

        $fromHeader = $headerMap['from'] ?? '';
        $senderName = null;
        $senderEmail = $fromHeader;

        if (preg_match('/^(.*?)\s*<(.+?)>$/', $fromHeader, $matches)) {
            $senderName = trim($matches[1], '"\' ');
            $senderEmail = trim($matches[2]);
        }

        $toHeader = $headerMap['to'] ?? '';
        $recipientEmail = $toHeader;
        if (preg_match('/<(.+?)>/', $toHeader, $toMatches)) {
            $recipientEmail = trim($toMatches[1]);
        }

        $subject = $headerMap['subject'] ?? '(No Subject)';
        $snippet = $rawMessage['snippet'] ?? '';

        // Extract plain and HTML body parts
        $bodyPlain = '';
        $bodyHtml = '';
        $hasAttachments = false;

        $this->extractBodyAndAttachments(
            $rawMessage['payload'] ?? [],
            $bodyPlain,
            $bodyHtml,
            $hasAttachments
        );

        $internalDate = $rawMessage['internalDate'] ?? null;
        $receivedAt = $internalDate
            ? Carbon::createFromTimestampMs((int) $internalDate)
            : now();

        return [
            'gmail_message_id' => (string) ($rawMessage['id'] ?? ''),
            'gmail_thread_id' => (string) ($rawMessage['threadId'] ?? ''),
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'recipient_email' => $recipientEmail,
            'subject' => $subject,
            'snippet' => $snippet,
            'body_plain' => $bodyPlain ?: null,
            'body_html' => $bodyHtml ?: null,
            'has_attachments' => $hasAttachments,
            'received_at' => $receivedAt,
        ];
    }

    /**
     * Recursively extract text/plain, text/html, and attachment flags from payload parts.
     *
     * @param  array<string, mixed>  $part
     */
    protected function extractBodyAndAttachments(array $part, string &$bodyPlain, string &$bodyHtml, bool &$hasAttachments): void
    {
        $mimeType = strtolower((string) ($part['mimeType'] ?? ''));
        $filename = (string) ($part['filename'] ?? '');

        if (! empty($filename) || ! empty($part['body']['attachmentId'])) {
            $hasAttachments = true;
        }

        $data = $part['body']['data'] ?? null;
        if (! empty($data)) {
            $decoded = $this->base64UrlDecode($data);

            if ($mimeType === 'text/plain' && empty($bodyPlain)) {
                $bodyPlain = $decoded;
            } elseif ($mimeType === 'text/html' && empty($bodyHtml)) {
                $bodyHtml = $decoded;
            }
        }

        if (! empty($part['parts']) && is_array($part['parts'])) {
            foreach ($part['parts'] as $subPart) {
                $this->extractBodyAndAttachments($subPart, $bodyPlain, $bodyHtml, $hasAttachments);
            }
        }
    }

    /**
     * Decode a base64url-encoded string.
     */
    protected function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return (string) base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Encode a string into base64url format without padding.
     */
    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Get or create a label by name and return its Gmail labelId.
     */
    public function getOrCreateLabel(User $user, string $labelName): ?string
    {
        try {
            $client = $this->getClient($user);

            // Check existing labels
            $listResponse = $client->get('/labels');
            if ($listResponse->successful()) {
                $labels = (array) ($listResponse->json('labels') ?? []);
                foreach ($labels as $label) {
                    if (strcasecmp((string) ($label['name'] ?? ''), $labelName) === 0) {
                        return (string) $label['id'];
                    }
                }
            }

            // Create label if not found
            $createResponse = $client->post('/labels', [
                'name' => $labelName,
                'labelListVisibility' => 'labelShow',
                'messageListVisibility' => 'show',
            ]);

            if ($createResponse->successful()) {
                return (string) $createResponse->json('id');
            }

            Log::warning("Failed to create Gmail label '{$labelName}' for user #{$user->id}: ".$createResponse->body());

            return null;
        } catch (Throwable $e) {
            Log::error("Exception in getOrCreateLabel '{$labelName}' for user #{$user->id}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Apply or remove labels on a message.
     *
     * @param  list<string>  $labelIdsToAdd
     * @param  list<string>  $labelIdsToRemove
     * @return array<string, mixed>
     */
    public function applyLabels(User $user, string $messageId, array $labelIdsToAdd = [], array $labelIdsToRemove = []): array
    {
        $response = $this->getClient($user)->post("/messages/{$messageId}/modify", [
            'addLabelIds' => $labelIdsToAdd,
            'removeLabelIds' => $labelIdsToRemove,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException("Failed to modify labels on Gmail message #{$messageId}: ".$response->body());
        }

        return (array) $response->json();
    }

    /**
     * Move a message to Trash in Gmail.
     */
    public function trashMessage(User $user, string $messageId): bool
    {
        $response = $this->getClient($user)->post("/messages/{$messageId}/trash");

        return $response->successful();
    }

    /**
     * Permanently delete a message in Gmail.
     */
    public function deleteMessage(User $user, string $messageId): bool
    {
        $response = $this->getClient($user)->delete("/messages/{$messageId}");

        return $response->successful();
    }

    /**
     * Send an email message via Gmail API.
     *
     * @return array<string, mixed>
     *
     * @throws RuntimeException
     */
    public function sendMessage(
        User $user,
        string $to,
        string $subject,
        string $body,
        ?string $threadId = null,
        ?string $inReplyTo = null,
    ): array {
        $from = $user->email;

        $rawMessage = "From: <{$from}>\r\n";
        $rawMessage .= "To: <{$to}>\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $rawMessage .= "Content-Transfer-Encoding: 7bit\r\n";

        if (! empty($inReplyTo)) {
            $rawMessage .= "In-Reply-To: <{$inReplyTo}>\r\n";
            $rawMessage .= "References: <{$inReplyTo}>\r\n";
        }

        $rawMessage .= "\r\n";
        $rawMessage .= $body;

        $payload = [
            'raw' => $this->base64UrlEncode($rawMessage),
        ];

        if (! empty($threadId)) {
            $payload['threadId'] = $threadId;
        }

        $response = $this->getClient($user)->post('/messages/send', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Failed to send email via Gmail API: '.$response->body());
        }

        return (array) $response->json();
    }
}
