---
description: Brand Workflow — Top-up Balance
---

# Brand Workflow — Top-up Balance

Source PRD: [`prd.md`](../../prd.md) §6.1

## PRD Flow

1. Brand opens **Finance** page.
2. Brand enters deposit amount.
3. System creates `deposits.status = pending`.
4. Midtrans Snap opens.
5. Midtrans webhook received.
6. On success, `deposits.status = success`; `users.balance += amount`.

## Current Progress

- [x] Route exists: `GET /brand/finance` → `brand.finance`.
- [x] Route exists: `POST /brand/finance/topup` → `brand.finance.topup`.
- [x] Route exists: `POST /midtrans/webhook` → `midtrans.webhook`.
- [x] Finance page loads authenticated brand deposits via `$user->deposits()`.
- [x] Top-up validates `amount >= 10000`.
- [x] Pending deposit created via `$user->deposits()->create(...)`.
- [x] Midtrans Snap token requested and saved to `deposits.snap_token`.
- [x] Webhook validates Midtrans signature.
- [x] Successful callback/webhook updates deposit to `success`.
- [x] Balance increment guarded by `if ($deposit->status === 'pending')` / early return when already success.

## Remaining Tasks

- [x] Add `expired` handling parity: schema allows `expired`, controller currently maps expire to `failed`.
- [x] Wrap deposit status update + balance increment in DB transaction.
- [x] Add feature tests for duplicate webhook idempotency.
- [x] Add UI state for failed/expired deposits if missing in finance view.
