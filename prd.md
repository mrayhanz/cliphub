# Product Requirement Document (PRD): ClipHub - Brand Module

## 1. Overview

ClipHub is a platform connecting brands with creators for user-generated content (UGC) campaigns. The **Brand Module** allows companies to fund their accounts, launch marketing campaigns, and review video submissions from creators.

## 2. Target Actor

- **Brand**: A business entity or individual seeking to promote products/services through creator-driven video content.

## 3. Functional Requirements

The Brand actor must be able to:

1.  Securely authenticate (Login/Register).
2.  Top-up balance via integrated payment gateways (Midtrans).
3.  Create and manage marketing campaigns with specific briefs and budgets.
4.  Monitor real-time campaign performance and escrow status.
5.  Review, approve, or reject creator submissions based on quality and analytics proof.

## 4. User Interface & Pages

| Page                  | Route                     | Function                                                                |
| :-------------------- | :------------------------ | :---------------------------------------------------------------------- |
| **Dashboard**         | `/brand/dashboard`        | Overview of active campaigns, total spending, and pending submissions.  |
| **Campaign Index**    | `/brand/campaigns`        | List of all campaigns created by the brand with status tracking.        |
| **Create Campaign**   | `/brand/campaigns/create` | Form to input campaign details, requirements, and budget allocation.    |
| **Submission Review** | `/brand/submissions`      | Interface to view creator videos, claim statistics, and proof of views. |
| **Finance / Top-up**  | `/brand/finance`          | Wallet management, deposit history, and Midtrans payment integration.   |
| **Brand Profile**     | `/brand/profile`          | Management of company identity, logo, and contact details.              |

## 5. Database Schema (Brand-Related)

### 5.1 Table: `users`

Stores account and financial balance data.

- `id`: Primary Key.
- `name`: Brand name.
- `email`: Authentication email.
- `role`: Set to `'brand'`.
- `balance`: Current available funds (bigint).

### 5.2 Table: `campaigns`

Stores specific marketing job details.

- `id`: Primary Key.
- `user_id`: Foreign Key (refers to the Brand/User).
- `title`: Campaign title.
- `budget`: Total allocated budget.
- `price_per_1k`: Payout rate per 1000 views.
- `slots`: Maximum number of accepted submissions.
- `full_brief`: Detailed instructions.
- `donts`: Negative constraints (things to avoid in videos).
- `status`: Current state (`active`, `completed`, `draft`).

### 5.3 Table: `deposits`

Tracks financial inflows.

- `id`: Primary Key.
- `user_id`: Foreign Key (refers to the Brand).
- `order_id`: Unique transaction identifier.
- `amount`: Deposited value.
- `status`: Payment status (`pending`, `success`, `failed`).
- `snap_token`: Midtrans integration token.

### 5.4 Table: `submissions`

Tracks work submitted to the brand for review.

- `id`: Primary Key.
- `campaign_id`: Foreign Key (refers to the specific Campaign).
- `video_url`: Link to the creator's content.
- `views_claimed`: Total views reported by the creator.
- `status`: Review status (`pending`, `approved`, `rejected`).

## 6. Core Workflows

### 6.1 Top-up Workflow

1.  Brand navigates to the **Finance** page.
2.  Brand enters the desired deposit amount.
3.  System creates a `pending` record in the `deposits` table.
4.  Midtrans Snap UI is triggered; Brand completes payment.
5.  System receives a Webhook from Midtrans.
6.  Upon success, the `deposits.status` is updated to `success` and `users.balance` is incremented.

### 6.2 Campaign Launch Workflow

1.  Brand accesses the **Create Campaign** form.
2.  Brand defines requirements (budget, brief, assets, platform).
3.  System validates that the Brand has sufficient `balance` to cover the `budget`.
4.  Data is saved to the `campaigns` table.
5.  Campaign becomes visible to Creators in the Marketplace.

### 6.3 Submission Review Workflow

1.  Brand receives notification of a new submission.
2.  Brand opens the **Submissions** list.
3.  Brand audits the `video_url` and `analytics_proof_path`.
4.  **Option A (Approve)**: Status updates to `approved`. Funds are calculated based on `views_claimed` and `price_per_1k`, then transferred from escrow to Creator wallet.
5.  **Option B (Reject)**: Brand provides a `rejection_reason`. Creator is notified to revise or the slot is reopened.
