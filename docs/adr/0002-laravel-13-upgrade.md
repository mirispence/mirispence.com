# ADR-0002: Upgrade to Laravel 13

## Status

Accepted.

## Context

The app ran Laravel 12.x (PHP 8.4). Laravel 13 shipped as the current stable major and requires only PHP 8.3+, already satisfied. The owner wants to stay near the front of the release curve rather than accumulate multi-major upgrade debt. Full research and grilling transcript preceded this change (see issue #16).

## Decisions

- **Bumped in one atomic PR**, not staged — research found no blockers or large breaking-change surface for this app's actual usage.
- `laravel/framework` `^12.0` → `^13.0`; forced companion bumps: `laravel/tinker` `^2.10.1` → `^3.0`, `laravel/boost` (dev) `^1.8` → `^2.0`, `spatie/laravel-permission` `^6.24` → `^8.0`, `spatie/laravel-sitemap` `^7.3` → `^8.0`. `laravel/fortify`, `laravel/horizon`, `laravel/wayfinder`, `laravel/pail`, `laravel/pint`, `laravel/sail`, `inertiajs/inertia-laravel`, `spatie/laravel-medialibrary`, `pestphp/pest-plugin-laravel`, and `larastan/larastan` already satisfied Laravel 13 within existing constraints.
- **`config/session.php`: `serialization` set to `'json'`** (previously unset, defaulting to PHP's native serialization). Deliberate, deployment-visible change — accepted trade-off is a one-time forced re-login/re-2FA-challenge for the admin session on deploy, in exchange for avoiding PHP-object-serialization security risk in stored session payloads.
- No other Laravel 13 defaults/scaffolding adopted — everything else in the upgrade guide either doesn't apply to this app's usage or wasn't "minor" enough to bundle into this ticket.
- `spatie/laravel-permission` v6→v8 and `spatie/laravel-sitemap` v7→v8: changelog-reviewed, no impact on `Gate::before`, `HasRoles`, `assignRole()`/`syncRoles()` (roles are passed by name/string here, not by ID — the v7 int-ID upgrade note doesn't apply), or sitemap generation (no custom crawl profiles/observers used).
- CSRF/Sec-Fetch-Site: no code change — `originOnly` left unset (default), which already falls back to token-based CSRF validation if the header is missing/altered. No known Cloudflare incompatibility.

## Consequences

- Session data stored via `database` driver is now JSON-serialized rather than PHP-serialized. Any code that assumed PHP-native session value types (there was none) would need reassessment before this could be reverted.
- Future dependency bumps that touch `spatie/laravel-permission` should re-check whether role/permission IDs are ever passed as numeric strings, since v7+ requires `int`.
- This ADR supersedes nothing in ADR-0001; it's an infrastructure version bump, not a stack-choice change.
