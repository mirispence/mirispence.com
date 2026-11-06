# Feature Flags

Tags: #infrastructure #feature-flags #pennant

## Overview

Laravel Pennant with the `array` store (ADR-0006: `docs/adr/0006-pennant-config-backed-feature-flags.md`). Every flag resolves from `config/features.php` (env-driven) and is global (default scope `null`). There is no per-user state and no database table.

| Flag | Env | Default |
|---|---|---|
| `books` | `FEATURE_BOOKS` | `false` |

Flags are defined in `AppServiceProvider::defineFeatures()` and shared to the frontend as the Inertia prop `features` (`HandleInertiaRequests::share()`, typed in `resources/js/types/index.d.ts`). Vue reads them with the `useFeatures()` composable (`resources/js/composables/useFeatures.ts`).

---

## `books` flag: effects when off

- Public `books.*` and admin `admin.books.*` / `admin.chapters.*` routes return 404 (`EnsureFeaturesAreActive::using('books')` in `routes/web.php`). Routes stay registered so Wayfinder can generate actions.
- Books link hidden in the public nav (desktop and mobile) and the admin nav.
- Home `featuredBooks` is `[]`; hero first line and home meta description drop "books".
- Sitemap omits `/books` and book URLs.
- Featured-item create/edit get no `books` list and no "Book" type; validation rejects `item_type=book`.
- Admin Tags skip the book count.
- Models, tables, data and the morph map are untouched.

With the flag on and zero published books, `/books` shows an empty state.

---

## Adding a flag

1. Add it to `config/features.php` and `.env.example`.
2. Define it in `AppServiceProvider::defineFeatures()`.
3. Add it to `features` in `HandleInertiaRequests::share()` and the `Features` type.
4. Gate routes with `EnsureFeaturesAreActive::using('<flag>')`; gate PHP with `Feature::active('<flag>')` and Vue with `useFeatures()`.
5. Keep routes registered (Wayfinder needs them).

---

## Testing

`phpunit.xml` sets `FEATURE_BOOKS=true`, so existing tests see Books on. To test the off state: `config(['features.books' => false])` then `Feature::flushCache()`. See `tests/Feature/BooksFeatureFlagTest.php` and `tests/Unit/FeatureFlagsTest.php`.

## Related

- [[Domains/Book & Chapter]]
- [[Routing/Routes]]
- [[Testing]]
