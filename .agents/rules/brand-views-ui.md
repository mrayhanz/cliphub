# Brand Rules — Views & UI

- All Brand pages must extend:
  ```blade
  @extends('layouts.brand')
  ```
- Define title/page title near top:
  ```blade
  @section('title', 'Brand Dashboard')
  @section('page_title', 'Brand Dashboard')
  ```
- Content wrapper should follow current spacing:
  ```blade
  @section('content')
  <div class="space-y-5 pb-8">
      ...
  </div>
  @endsection
  ```
- Keep premium dark UI style:
  - Background: `#060606`, glass cards, border `white/[0.05]` or inline `rgba(255,255,255,0.05)`.
  - Accent: emerald/brand green (`text-brand`, `text-emerald-400`, `rgba(16,185,129,...)`).
  - Typography: bold/black headings, small uppercase metadata labels.
  - Existing classes: `hero-card`, `glass-card`, `stat-card`, `btn-primary`, `btn-ghost`, `icon-box-green`, `icon-box-amber`, `icon-box-slate`.
  - Icons use Lucide: `<i data-lucide="..."></i>`.
  - Use `animate-fade-in-up` and delay classes where matching cards/lists.
- Use Indonesian UI copy.
- Use `route('brand...')` helpers; no hard-coded `/brand/...` links.
- Format money with Indonesian separators:
  ```blade
  Rp {{ number_format($amount, 0, ',', '.') }}
  ```
- Use responsive Tailwind classes consistent with existing views: `grid grid-cols-1 lg:grid-cols-3`, `text-xs lg:text-sm`, etc.
- Avoid creating a new layout unless explicitly requested.
