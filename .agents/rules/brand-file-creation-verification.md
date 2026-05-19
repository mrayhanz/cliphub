# Brand Rules — File Creation, Anti-Patterns, Verification

## File Creation Rules

When adding a new Brand feature named `<feature>`:

1. Controller: `app/Http/Controllers/Brand/<Feature>Controller.php`.
2. Views: `resources/views/brand/<feature>/index.blade.php` plus action views as needed.
3. Routes: inside existing `brand` group with names `brand.<feature>` etc.
4. DB: migration only if existing schema cannot support feature.
5. Sidebar/navbar: update `resources/views/brand/partials/` only if navigation needs new item.
6. Tests: add feature tests under `tests/Feature/Brand/` when behavior is non-trivial.

## Anti-Patterns

- Do not create `resources/views/brands/...`; use singular `brand`.
- Do not create controllers outside `App\Http\Controllers\Brand` for brand dashboard pages.
- Do not use generic `role:brand` middleware unless project has that middleware; current project uses `IsBrand::class`.
- Do not hard-code URLs where named routes exist.
- Do not bypass authenticated ownership filters.
- Do not change `users.role` enum values without checking auth middleware, seeders, login redirects, and docs.
- Do not replace the brand layout/design system with a new one for a single page.
- Do not store uploaded campaign thumbnails outside the `public` disk unless storage access rules are also updated.

## Verification Checklist

After changes, verify:

- `php artisan route:list --name=brand`
- Brand route names resolve from Blade links.
- Authenticated `brand` user can access pages.
- Non-brand users are blocked by `IsBrand`.
- Created/queried records are scoped to `auth()->user()`.
- Blade page extends `layouts.brand` and uses current dark/glass UI classes.
- Money displays with `number_format(..., 0, ',', '.')`.
- Any migration aligns with existing FK + naming style.
