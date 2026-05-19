---
description: Brand Workflow — Dashboard Monitoring
---

# Brand Workflow — Dashboard Monitoring

Source PRD: [`prd.md`](../../prd.md) §4 Dashboard, §3.4

## PRD Flow

Dashboard shows active campaigns, total spending, pending submissions, campaign performance, escrow status.

## Current Progress

- [x] Route exists: `GET /brand/dashboard` → `brand.dashboard`.
- [x] Controller loads authenticated brand user.
- [x] Controller loads balance.
- [x] Controller loads latest 5 brand campaigns.
- [x] Escrow calculated canonically (budget minus approved payouts).
- [x] Total views, total UGC, and pending review loaded live.
- [x] UI exists with active campaign, budget, pending review cards, and graphical statistic.

## Remaining Tasks

- [x] Define canonical escrow calculation.
- [x] Query pending submissions through owned campaigns.
- [x] Query approved submissions / total UGC.
- [x] Sum views from approved or submitted records.
- [x] Add spending calculation based on approved payouts/deposits/campaign budgets.
- [x] Add tests for dashboard metrics.
