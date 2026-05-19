# Brand Rules — Overview & Planning

Use before generating/refactoring **Brand** code. Match current project style, not generic Laravel style.

## Current Brand Path Map

- Controllers: `app/Http/Controllers/Brand/`
  - `DashboardController.php`
  - `CampaignController.php`
  - `FinanceController.php`
- Views: `resources/views/brand/`
  - `dashboard/index.blade.php`
  - `campaigns/index.blade.php`, `campaigns/create.blade.php`
  - `finance/index.blade.php`
  - `submissions/index.blade.php`
  - `profile/index.blade.php`
  - `partials/sidebar.blade.php`, `partials/navbar.blade.php`
- Layout: `resources/views/layouts/brand.blade.php`
- Routes: `routes/web.php`, Brand group uses `auth` + `IsBrand::class`, `prefix('brand')`, `name('brand.')`.
- Brand role DB source: `users.role = enum('admin', 'kreator', 'brand')`.

## Mandatory Planning Flow

Before changing Brand features, inspect:

1. Existing controller in `app/Http/Controllers/Brand/`.
2. Existing Blade folder in `resources/views/brand/<feature>/`.
3. Brand route group in `routes/web.php`.
4. Related model + migration schema in `database/migrations/`.
5. Existing UI components/classes in brand layout + nearby Blade pages.

Do not invent new structure if current project already has one.
