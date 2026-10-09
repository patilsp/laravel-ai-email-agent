# Software Requirements Specification (SRS) & Scope of Project

**Project Name:** Email AI Agent  
**Version:** 1.0.0 (Architecture Baseline)  
**Status:** DRAFT → PROPOSED  
**Stack:** Laravel 12 (PHP 8.2+), Tailwind CSS v4, Anthropic Claude 3.5 Sonnet (`laravel/ai`), Google Gmail API (OAuth 2.0)  
**Primary Paradigm:** Semi-Autonomous Human-in-the-Loop Email Operations

---

## 1. Executive Summary & Vision

The **Email AI Agent** is an intelligent inbox assistant designed to transform incoming email streams into prioritized, categorized, and actionable items. Instead of autonomous "ghost" sending, the agent operates in a **semi-automatic** mode: it ingests unread emails, uses **Anthropic Claude 3.5 Sonnet** to extract intent, urgency, and context, suggests Gmail labels and priorities, pre-composes professional response drafts, and queues them in a unified web dashboard for **one-click human review and approval**.

---

## 2. Project Scope & Boundaries

### 2.1 In-Scope Capabilities
- **Google OAuth 2.0 Integration:** Secure user authentication and token management with granular Gmail API scopes (`gmail.modify`, `gmail.compose`, `gmail.labels`).
- **Scheduled Background Ingestion Engine:** Periodic polling (every 1–3 minutes via Laravel Scheduler) of eligible unread messages and updated thread histories.
- **Anthropic Claude AI Analysis Pipeline:**
  - **Intent Extraction:** Determine whether the email requires a reply, meeting scheduling, document review, or simple acknowledgement.
  - **Urgency & Deadline Scoring:** Identify time-sensitive constraints and score priority (`Urgent`, `Important`, `Routine`).
  - **Sentiment & VIP Recognition:** Gauge sender tone (frustrated, inquiring, neutral, delighted) and highlight key contacts.
  - **Smart Gmail Label Proposal:** Assign structured tags (`Client`, `Invoices/Finance`, `Team/Internal`, `Follow-up`, `Newsletters`).
  - **Contextual Draft Generation:** Pre-compose polite, context-grounded response drafts matching professional communication standards.
- **Human-in-the-Loop Review Dashboard:**
  - Visual queue of all incoming messages awaiting action.
  - Side-by-side view of original email, Claude's reasoning breakdown, and proposed draft.
  - Live draft editor allowing quick customization before sending.
  - Three distinct decision triggers: `Approve & Send`, `Edit Draft`, or `Discard/Reject`.
- **Outbound Dispatch & Gmail Sync:**
  - Execute approved actions via Gmail API (send message, save to Gmail drafts, apply labels, star priority).
  - Comprehensive audit trail logging every AI proposal and human decision.

### 2.2 Out-of-Scope (Phase 1)
- Autonomous direct auto-sending without human approval (explicitly prohibited by architectural rule).
- Multi-provider email support (Outlook/Exchange/IMAP will be evaluated in Phase 2).
- Real-time Google Cloud Pub/Sub push webhooks (polling selected for local/XAMPP reliability and zero-tunnel requirements).
- Full CRM contact enrichment or outbound mass-marketing campaigns.

---

## 3. Technical Constraints & Architecture Choices

| Component | Choice | Rationale |
|---|---|---|
| **Framework** | Laravel 12 (PHP 8.2+) | Modern, robust queue/scheduler ecosystem, native Eloquent ORM. |
| **AI SDK** | `laravel/ai` (v1.2.0) | Official first-party SDK providing structured output, token tracking, and agent abstractions. |
| **AI Model** | Anthropic Claude 3.5 Sonnet | Unmatched nuanced reading comprehension, reasoning depth, and natural conversational writing tone. |
| **Email Protocol** | Gmail REST API v1 | Official Google REST API with granular OAuth scopes. |
| **Ingestion Method** | Scheduled Polling (1–3 min) | Zero external webhook tunnels required; perfectly matches semi-automatic review rhythm. |
| **Frontend** | Blade + Tailwind CSS v4 + Vanilla JS | Ultra-fast load times, lightweight, zero heavy framework overhead. |
| **Security** | AES-256 Token Encryption | Google OAuth access/refresh tokens encrypted at rest via Laravel Crypt. |

---

## 4. Functional Requirements

### FR-1: Account & Inbox Linking
- **FR-1.1:** Users can authenticate via Google OAuth 2.0 with requested Gmail scopes.
- **FR-1.2:** System securely stores encrypted OAuth tokens with automated refresh token rotation.
- **FR-1.3:** Users can disconnect/revoke access at any time from settings.

### FR-2: Background Ingestion & State Machine
- **FR-2.1:** Scheduler runs `email-agent:poll` periodically to fetch unread messages (`is:unread`).
- **FR-2.2:** Avoid redundant processing by storing message `history_id` and unique message IDs.
- **FR-2.3:** Parse multi-part MIME email bodies, stripping HTML clutter to generate clean plain-text context for AI.

### FR-3: AI Cognition & Reasoning Engine
- **FR-3.1:** Feed thread context and sender history to Anthropic Claude 3.5 Sonnet.
- **FR-3.2:** Return structured JSON schema containing:
  - `intent`: Primary purpose and required action.
  - `urgency_score`: Numeric scale (1–10) and category (`Urgent`, `Important`, `Routine`).
  - `sentiment`: Detected sender tone.
  - `deadline`: Extracted timestamp or date constraints (if present).
  - `suggested_labels`: Array of proposed Gmail labels.
  - `suggested_reply`: Complete drafted response text.
  - `requires_reply`: Boolean flag.

### FR-4: Human-in-the-Loop Review Dashboard
- **FR-4.1:** Display active queue of pending reviews with sorting by urgency score.
- **FR-4.2:** Provide inline draft editor with real-time text updates.
- **FR-4.3:** Action Triggers:
  - `Approve`: Dispatches reply via Gmail API and applies proposed labels.
  - `Edit & Send`: Sends modified draft.
  - `Discard`: Marks item resolved without sending, maintaining label updates if desired.

### FR-5: Audit & Security Logging
- **FR-5.1:** Record every message processed, AI proposal, human modification diff, and dispatch timestamp.

---

## 5. Non-Functional Requirements

- **Security & Privacy:** Scoped OAuth permissions; tokens encrypted at rest; zero model training on customer data (Anthropic commercial API).
- **Reliability & Idempotency:** Guard against double-sending with database transactional locks and atomic state transitions.
- **Performance:** Triage analysis completed within ≤ 2.5 seconds per email thread.
- **Accessibility & Responsiveness:** Fully responsive interface across mobile (320px+), tablet, and desktop viewports.
