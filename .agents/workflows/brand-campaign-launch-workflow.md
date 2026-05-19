---
description: Brand Workflow — Campaign Launch
---

# Brand Workflow — Campaign Launch

Source PRD: [`prd.md`](../../prd.md) §6.2

## PRD Flow

1. Brand opens **Create Campaign** form.
2. Brand defines budget, brief, assets, platform.
3. System validates sufficient brand `balance` for `budget`.
4. Data saved to `campaigns`.
5. Campaign becomes visible to Creators marketplace.

## Current Progress

- [x] Route exists: `GET /brand/campaigns` → `brand.campaigns`.
- [x] Route exists: `GET /brand/campaigns/create` → `brand.campaigns.create`.
- [x] Route exists: `POST /brand/campaigns` → `brand.campaigns.store`.
- [x] `CampaignController@index` lists only authenticated brand campaigns.
- [x] `CampaignController@create` returns `brand.campaigns.create`.
- [x] `CampaignController@store` validates core campaign fields from PRD.
- [x] Thumbnail stored on `public` disk under `campaigns`.
- [x] Campaign ownership guaranteed via `$user->campaigns()->create(...)`.
- [x] Draft/active status selected from request action.
- [/] Marketplace visibility likely works for active campaigns through Kreator campaign listing, but not verified here.

- [x] Validate `users.balance >= campaign.budget` before storing active campaign.
- [x] Define escrow model: deduct from available balance or compute locked budget separately.
- [x] Prevent active campaign launch when balance insufficient.
- [x] Add edit/update/cancel/complete management actions if “manage campaigns” means beyond create/list.
- [x] Modernized UI across `index.blade.php`, `create.blade.php`, and `show.blade.php` (consistent typography, card design).
- [x] Implemented global Alpine.js confirmation modals for handling safe Escrow transactions.
- [x] Seeded relational mock data via `CampaignSeeder` and `SubmissionSeeder`.
- [x] Add tests for brand-owned scoping and insufficient balance.
