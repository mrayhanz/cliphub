---
description: Brand Workflow — Profile Management
---

# Brand Workflow — Profile Management

Source PRD: [`prd.md`](../../prd.md) §4 Brand Profile

## PRD Flow

Brand manages company identity, logo, contact details.

## Current Progress

- [x] Route exists: `GET /brand/profile` → `brand.profile`.
- [x] View folder exists: `resources/views/brand/profile/index.blade.php`.
- [x] Page uses dynamic controller routing (`Brand\ProfileController`).
- [x] Profile controller and update route exist with logo upload handling.
- [x] Created `brand_profiles` table and model with relation to `users` model.

## Remaining Tasks

- [x] Decide whether profile fields live on `users` or new `brand_profiles` table (implemented dedicated `brand_profiles` table).
- [x] Add migration for company logo/contact metadata.
- [x] Create `Brand\ProfileController@index/update`.
- [x] Add validation + file upload for logo.
- [x] Add tests for update and file storage.
