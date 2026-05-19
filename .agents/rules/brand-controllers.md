# Brand Rules — Controllers

- Namespace must be `App\Http\Controllers\Brand`.
- Extend `App\Http\Controllers\Controller`.
- Keep controller names simple: `<Feature>Controller`.
- Use current auth style:
  ```php
  /** @var \App\Models\User $user */
  $user = auth()->user();
  ```
- Prefer relationship queries from `$user` for brand-owned data:
  ```php
  $campaigns = $user->campaigns()->latest()->get();
  $deposits = $user->deposits()->orderBy('created_at', 'desc')->get();
  ```
- Return views using `brand.<folder>.<file>`.
- Use `compact(...)` as existing controllers do.
- Validate request data inside `store/update` using `$request->validate([...])`.
- Use Indonesian success/error messages to match current app copy.
- Keep brand access enforced by route middleware `IsBrand`, not repeated role checks unless feature requires extra ownership validation.
- Money/balance/payment logic must be idempotent; never increment balance twice for same successful deposit.
