# Brand Rules — Database, Role, Ownership

## `users` table

Source: `database/migrations/0001_01_01_000000_create_users_table.php`

- `role`: enum `admin`, `kreator`, `brand`; default `kreator`.
- `balance`: `bigInteger`, default `0`.
- Brand wallet/top-up features must use `users.balance`.
- Brand-owned records should relate to `users.id` via `user_id`.

## `campaigns` table

Source: `database/migrations/2026_03_29_105722_create_campaigns_table.php`

- Brand owner FK:
  ```php
  $table->foreignId('user_id')->constrained()->onDelete('cascade');
  ```
- Existing fields: `title`, `type`, `slots`, `thumbnail`, `desc`, `full_brief`, `donts`, `assets_url`, `deadline`, `video_length`, `link`, `platform`, `budget`, `price_per_1k`, `status`.
- `type` currently expected: `video`, `clip`.
- `status` current comment: `draft`, `active`, `completed`, `cancelled`.
- Create campaigns through `$user->campaigns()->create([...])` to guarantee ownership.

## `deposits` table

Source: `database/migrations/2026_03_29_114247_create_deposits_table.php`

- Brand top-up records belong to user via `user_id`.
- Payment flow must preserve current statuses: `pending`, `success`, `failed`.
- Webhook/callback code must check current status before updating balance.

## Other related tables

- `submissions`: kreator submissions against campaigns; brand pages should query via campaigns they own.
- `clips`: AI clipping outputs; only expose to brand if linked through owned campaign/submission.
- `withdrawals`: kreator finance, not brand top-up.

## Ownership paths

- Brand can only access records owned through their `users.id`.
- Campaign: `users(id, role=brand) → campaigns.user_id`.
- Deposit: `users(id, role=brand) → deposits.user_id`.
- Submission: `users(id, role=brand) → campaigns.user_id → submissions.campaign_id`.
- Never query all campaigns/submissions/deposits on Brand pages without filtering by authenticated brand.
