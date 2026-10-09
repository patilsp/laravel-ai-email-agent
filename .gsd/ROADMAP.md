# Implementation Roadmap & Milestone Plan

**Project:** Email AI Agent  
**Goal:** Deliver a production-grade, semi-autonomous Gmail assistant powered by Laravel 12 and Anthropic Claude 3.5 Sonnet.  
**Overall Status:** COMPLETED & VERIFIED (All 7 Phases 100% Implemented)

---

## Phase Breakdown

### Phase 1: Authentication & Google OAuth 2.0 Integration
- **Objective:** Enable secure user login and Gmail API authorization with encrypted token storage and automatic refresh.
- **Deliverables:**
  - [x] Installed and configured `laravel/socialite` with required Gmail scopes (`gmail.modify`, `gmail.compose`, `gmail.labels`, `email`, `profile`).
  - [x] Database migration for `oauth_tokens` table with AES-256 encrypted `access_token` and `refresh_token`.
  - [x] `OAuthToken` and `User` Eloquent relationships and state helpers.
  - [x] `GoogleTokenService` with automated expiration detection, token refresh against Google's OAuth2 endpoints, and token revocation.
  - [x] `GoogleAuthController` managing OAuth redirect, callback, account disconnection, and logout.
- **Verification Proof:** 14 automated tests passing (41 assertions).

---

### Phase 2: Database Architecture & Data Models
- **Objective:** Create complete relational database schema and Eloquent models for message storage and triage states.
- **Deliverables:**
  - [x] Database migration `email_messages` for storing message/thread IDs, headers, snippets, plain/HTML bodies, attachment flags, and compound indexes.
  - [x] Database migration `ai_analyses` for storing Claude intent classifications, urgency levels & 1.0–5.0 scores, sentiment, deadlines, suggested labels, summaries, and tokens.
  - [x] Database migration `email_drafts` for managing proposed AI drafts, human revisions, lifecycle status (`pending`, `approved`, `edited`, `rejected`, `sent`), and Gmail sent message IDs.
  - [x] Database migration `audit_logs` for append-only activity logging with JSON metadata.
  - [x] Eloquent models (`EmailMessage`, `AiAnalysis`, `EmailDraft`, `AuditLog`, `User`) with casts, relationships, scopes, and helper methods.
  - [x] Realistic model factories for all models.
- **Verification Proof:** 5 dedicated feature tests passing with 39 assertions.

---

### Phase 3: Claude 3.5 Sonnet AI Analysis Pipeline (`laravel/ai`)
- **Objective:** Implement structured email triage agent and queued analysis pipeline using Anthropic Claude 3.5 Sonnet.
- **Deliverables:**
  - [x] Configured `laravel/ai` SDK with Anthropic driver and Claude 3.5 Sonnet (`claude-3-5-sonnet-20241022`).
  - [x] Implemented `App\Ai\Agents\EmailTriageAgent` implementing `Agent` and `HasStructuredOutput` with complete JSON schema.
  - [x] Created `EmailAnalyzerService` with prompt building, structured JSON parsing, atomic DB transactions, draft creation for actionable emails, and audit logging.
  - [x] Built `AnalyzeEmailJob` queued job with 3 retries, exponential backoff, and audit logging on permanent failures.
- **Verification Proof:** 5 dedicated feature tests passing with 28 assertions.

---

### Phase 4: Scheduled Background Polling & Gmail Ingestion Engine
- **Objective:** Ingest unread messages from Gmail API, deduplicate, and trigger triage analysis.
- **Deliverables:**
  - [x] `App\Services\GmailApiService` built on Laravel `Http` client:
    - Message querying (`is:unread -label:AI_PROCESSED`).
    - Multi-part MIME tree traversal extracting plain/HTML bodies, attachment flags, and Base64URL decoding.
    - Label checking and auto-creation (`getOrCreateLabel`).
    - Label modification on Gmail threads.
    - RFC 2822 email composition and sending.
    - Message trashing and permanent deletion.
  - [x] `App\Jobs\PollGmailInboxJob`: Queued polling job that deduplicates messages, creates `EmailMessage` records, attaches `AI_PROCESSED` label to prevent reprocessing, and dispatches `AnalyzeEmailJob`.
  - [x] `App\Console\Commands\SyncGmailInboxCommand` (`php artisan email:sync`): Artisan command supporting global or user-specific sync.
  - [x] `routes/console.php`: Scheduled to run `email:sync` every 2 minutes with overlap protection.
- **Verification Proof:** 7 dedicated feature tests passing with 28 assertions.

---

### Phase 5: Human-in-the-Loop Approval & Action Execution Engine
- **Objective:** Empower human review, draft editing, one-click sending, label syncing, and message trashing.
- **Deliverables:**
  - [x] `App\Services\EmailActionService`: Centralized domain service enforcing strict ownership authorization for:
    - `approveAndSendDraft`: Dispatches reply via Gmail API in RFC 2822 format with `In-Reply-To` threading, marks draft `sent`, and records audit log.
    - `editDraft`: Saves human revision, sets status to `edited`, logs audit event.
    - `rejectDraft`: Sets status to `rejected`, logs audit event.
    - `applySuggestedLabels`: Creates and applies suggested labels on Gmail threads.
    - `trashEmail`: Moves email to Trash in Gmail and deletes local record.
    - `sendCustomEmail`: Composes and sends fresh emails via Gmail API.
  - [x] Form Request validators: `EditDraftRequest` and `SendCustomEmailRequest`.
  - [x] `EmailActionController` with RESTful JSON and web redirect endpoints.
  - [x] Registered routes in `routes/web.php` under `auth` middleware.
- **Verification Proof:** 7 dedicated feature tests passing with 30 assertions.

---

### Phase 6: Intelligent Live Dashboard & Interactive Web Workspace
- **Objective:** High-density, 3-column AI Email Workspace for managing triage queues.
- **Deliverables:**
  - [x] Upgraded `DashboardController` calculating live user KPIs, multi-field search, multi-state filtering (`all`, `urgent`, `important`, `pending`, `sent`), and eager loading.
  - [x] Manual "Sync Inbox" route `POST /dashboard/sync`.
  - [x] Built `resources/views/dashboard.blade.php`:
    - Top navigation with Google connection indicator, manual sync trigger, and compose modal trigger.
    - 4 KPI metric cards.
    - Column 1: Filterable email feed with urgency badges, intent chips, draft ready tags, and timestamps.
    - Column 2: Selected email reader with subject, headers, attachment indicators, and clean reading surface.
    - Column 3: AI Intelligence Rail with Claude 3.5 Sonnet analysis, 1.0–5.0 urgency rating, executive summary, intent, sentiment, deadlines, suggested labels, and interactive draft editor ("Approve & Send", "Save Edit", "Reject Draft", sent status, audit trail).
    - Compose Modal: Pop-up modal for composing and dispatching custom emails.
  - [x] Compiled assets with Tailwind CSS v4 and Vite (`npm run build`).
- **Verification Proof:** 5 dedicated feature tests passing with 18 assertions.

---

### Phase 7: End-to-End Hardening, Verification & Documentation
- **Objective:** Validate end-to-end integration across all subsystems, maintain 100% test coverage, and complete documentation.
- **Deliverables:**
  - [x] Full End-to-End integration test (`EndToEndEmailWorkflowTest`) verifying the complete lifecycle: OAuth connection -> Gmail polling -> Claude 3.5 Sonnet analysis -> Dashboard workspace review -> Human draft revision -> One-click approval & dispatch -> Gmail API send -> Complete audit trail verification.
  - [x] **44 automated tests passing (211 assertions)** across the entire test suite.
  - [x] Code style formatted and validated with **Laravel Pint**.
  - [x] Comprehensive documentation updated (`.gsd/SPEC.md`, `.gsd/ROADMAP.md`, `.gsd/STATE.md`, `README.md`, `docs/ARCHITECTURE.md`, `docs/PRD.md`).
- **Verification Proof:** Full test suite execution: `44 passed (211 assertions)`, duration ~15s.
