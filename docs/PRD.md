# Product Requirements Document (PRD)

**Product:** Email AI Agent  
**Document Status:** Version 1.0 (Approved for Development)  
**Target Release:** MVP / Private Beta  

---

## 1. Problem Statement

Professionals and executives receive between 50 to 120 emails daily. More than 25% of their working hours are consumed by:
1. Skimming redundant threads to find action items.
2. Manually sorting messages into folders and labels.
3. Drafting repetitive replies and scheduling acknowledgements.
4. Risking missed deadlines due to buried urgent requests.

Existing "autonomous AI email bots" fail because they send inaccurate or embarrassing emails without human oversight. Users want **speed and intelligence without surrendering control**.

---

## 2. Product Vision & Value Proposition

**Email AI Agent** is an intelligent semi-autonomous workspace that turns chaotic email streams into a structured, prioritized action queue. It reads, comprehends, tags, and pre-writes responses with Anthropic Claude 3.5 Sonnet, presenting everything in an executive dashboard where the user retains the final approval over every outgoing action.

---

## 3. User Personas

### Persona A: The Startup Founder / Executive (Alex)
- **Pain Points:** 100+ daily emails across investors, legal counsel, team members, and enterprise customers. Struggles to spot time-sensitive term sheet inquiries among newsletters.
- **Goals:** Zero missed critical deadlines; 1-click approvals for standard scheduling and document acknowledgements.

### Persona B: The Engineering Lead / Architect (Marcus)
- **Pain Points:** Long technical threads with multiple back-and-forth replies. Needs to quickly understand what decision is blocked on them.
- **Goals:** 2-sentence thread briefings and pre-formulated technical replies without context-switching fatigue.

---

## 4. User Journey & Core Flow

```
[ Step 1: Connect ]
User signs in and authorizes Google OAuth with Gmail permissions in 15 seconds.
       │
       ▼
[ Step 2: Background Ingestion ]
Laravel scheduler polls inbox in background every 2 minutes.
       │
       ▼
[ Step 3: Claude Analysis & Draft Generation ]
Claude scores urgency (1-10), extracts deadlines, assigns labels, and drafts polite responses.
       │
       ▼
[ Step 4: Executive Review Dashboard ]
User opens web dashboard, views urgent messages at the top with pre-written drafts.
       │
       ▼
[ Step 5: One-Click Approval ]
User reviews draft -> taps [Approve & Send] -> Agent dispatches reply & updates Gmail labels.
```

---

## 5. Feature Requirements & Acceptance Criteria

| Feature Area | User Story | Acceptance Criteria |
|---|---|---|
| **Google OAuth** | As a user, I want to connect my Gmail inbox in one click without sharing passwords. | Successful OAuth consent flow; encrypted tokens stored; automated refresh mechanism. |
| **Urgency Triage** | As a user, I want urgent client inquiries surfaced above marketing emails. | Messages classified as `Urgent`, `Important`, or `Routine` with explicit deadline alerts. |
| **Smart Labels** | As a user, I want incoming emails automatically tagged in Gmail. | Suggested labels proposed in dashboard; applied to Gmail on approval. |
| **Response Drafts** | As a user, I want high-quality response drafts ready to review. | Drafts pre-composed using thread context, polite professional tone, and specific dates. |
| **Human Review** | As a user, I want to edit, approve, or discard drafts before anything is sent. | Interactive review drawer with live textarea editing; zero blind sends. |
| **Audit Trail** | As a user, I want a log of all actions taken by the AI. | Timestamped log of ingested messages, AI proposals, and operator approvals. |

---

## 6. Success Metrics & Key Performance Indicators (KPIs)

1. **Inbox Triage Time:** Reduce daily time spent on email by ≥ 60% (from 90 mins to ≤ 30 mins).
2. **Draft Acceptance Rate:** ≥ 75% of Claude-generated drafts approved with minimal or zero edits.
3. **Zero False Outbound Sends:** 100% human-verified outbound dispatch rate (0 ghost sends).
4. **Latency:** End-to-end background ingestion and AI analysis completed in ≤ 3.0 seconds per thread.
