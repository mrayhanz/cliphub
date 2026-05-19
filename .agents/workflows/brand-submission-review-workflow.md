---
description: Brand Workflow — Submission Review
---

# Brand Workflow — Submission Review

Source PRD: [`prd.md`](../../prd.md) §6.3

## PRD Flow

1. Brand receives notification of new submission.
2. Brand opens **Submissions** list.
3. Brand audits `video_url` and `analytics_proof_path`.
4. Approve: `status = approved`; reward calculated by `views_claimed * price_per_1k / 1000`; funds transferred from escrow to creator wallet.
5. Reject: set `rejection_reason`; notify creator/reopen slot.

## Current Progress

- [x] Route exists: `GET /brand/submissions` → `brand.submissions`.
- [x] View exists: `resources/views/brand/submissions/index.blade.php`.
- [x] DB schema supports `video_url`, `views_claimed`, `analytics_proof_path`, `estimated_reward`, `status`, `rejection_reason`.
- [x] Dashboard pending review count calculated canonically via real database queries.
- [x] Brand `SubmissionController` fully implemented with scoping.
- [x] Secure POST approve/reject routes and action handlers set up.
- [x] Atomic transactional escrow-to-creator wallet payout logic implemented.
- [x] Real-time interactive details modals, calculator cards, and confirmation modal calculations added.

## Remaining Tasks

- [x] Create `App\\Http\\Controllers\\Brand\\SubmissionController`.
- [x] Replace closure route with controller `index` scoped through owned campaigns.
- [x] Add `approve` route/action.
- [x] Add `reject` route/action with `rejection_reason` validation.
- [x] Calculate reward from campaign `price_per_1k` + submission `views_claimed`.
- [x] Increment creator `users.balance` on approval.
- [x] Ensure approval idempotency; never pay twice.
- [x] Update dashboard pending review count from real query.
- [x] Add tests for owner scoping, approve, reject, duplicate approval.
