# ADR-0006: Feature flags with Pennant on a config-backed `array` store

## Status

Accepted (2026-11-05). Implemented in #45. The first flag is `books`.

## Context

The owner wants to switch whole areas of the site off by config (starting with Books and Chapters) without deleting code, tables or data. Flags must be global: admin and guest visitors see the same state.

## Decision

- Use **Laravel Pennant** (first-party).
- Every flag's value comes from `config/features.php` (env-backed, e.g. `FEATURE_BOOKS`). `AppServiceProvider::defineFeatures()` defines each one as `Feature::define('<name>', fn () => (bool) config('features.<name>'))`.
- Use Pennant's **`array` store** (`PENNANT_STORE=array`, also the default in `config/pennant.php`). No Pennant migration is installed.
- The default scope resolves to `null` (`Feature::resolveScopeUsing(fn () => null)`), so `Feature::active('<name>')` gives the global value everywhere, guests included.
- Gated routes use `EnsureFeaturesAreActive::using('<name>')`. Its inactive response is set to a **404**, so a switched-off area looks absent rather than forbidden.
- Active flags are shared with Inertia as `features` (`HandleInertiaRequests`) and read in Vue via `useFeatures()`.

## Considered options

- **Pennant `database` store.** Rejected: it saves the first value it resolves, so flipping the env var later would do nothing until someone ran `pennant:purge`. That defeats "disable by config".
- **Plain `config()` checks with no Pennant.** Workable for one flag, but the issue asked for Pennant's middleware and conventions, and it leaves room for per-scope flags later.
- **Not registering routes when a flag is off.** Rejected: Wayfinder generates its TypeScript from the route list, so the frontend imports would break whenever a flag was off at build time.

## Consequences

- Changing a flag means changing the env var and clearing the config cache (`php artisan config:cache` on deploy). There is no admin UI for flags.
- Per-user or percentage rollouts would need a different store and resolver. That is out of scope.
- Tests: `phpunit.xml` sets `FEATURE_BOOKS=true`, so the existing suites run with books on. Flag-off tests set `config(['features.books' => false])` and call `Feature::flushCache()`.
