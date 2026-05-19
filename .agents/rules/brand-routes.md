# Brand Rules — Routes

- Add Brand routes only inside existing Brand group:
  ```php
  Route::middleware(['auth', \App\Http\Middleware\IsBrand::class])->prefix('brand')->name('brand.')->group(function () {
      // ...
  });
  ```
- Keep route names under `brand.`.
- Current route naming style avoids `.index` for main pages:
  - `brand.dashboard`
  - `brand.campaigns`
  - `brand.campaigns.create`
  - `brand.campaigns.store`
  - `brand.submissions`
  - `brand.finance`
  - `brand.finance.topup`
  - `brand.profile`
- Use fully-qualified controller class references as current file does:
  ```php
  [\App\Http\Controllers\Brand\CampaignController::class, 'index']
  ```
- Keep comments short and sectioned like `// Brand Campaigns`.
- For static pages already simple, closures returning `view('brand.<folder>.index')` are acceptable.
