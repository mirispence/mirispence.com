# Testing

Tags: #testing #pest #feature #unit

## Test Runner

**Pest 4** with `pest-plugin-laravel`. Underlying engine is PHPUnit.

Default command:
```bash
php artisan test
```

Tests use an **in-memory SQLite** database (configured in `phpunit.xml`).

---

## Test Suite Structure

```
tests/
├── Feature/
│   ├── Admin/           # Admin CRUD feature tests
│   ├── Auth/            # Fortify auth flow tests
│   ├── Settings/        # Settings feature tests
│   ├── PublicSiteTest.php
│   ├── SecurityFixesTest.php
│   ├── DashboardTest.php
│   └── PaginationStructureTest.php
├── Unit/
│   ├── ExampleTest.php
│   └── SeoBuilderTest.php
├── Pest.php             # Pest configuration
└── TestCase.php
```

---

## Coverage Areas

### `PublicSiteTest.php`

Covers all public routes:
- Home page renders
- Art index (with/without filters), art show (published and draft 404)
- Galleries index, gallery show (with artwork pagination)
- Books index, book show
- Contact form GET + POST (with validation, rate limiting)

### `tests/Feature/Auth/`

Full Fortify authentication flow:
- Login / logout
- Registration
- Password reset (request + reset)
- Email verification
- Two-factor authentication enable/confirm/disable

### `tests/Feature/Admin/`

Admin CRUD for all resources:
- Artworks (index, create, store, edit, update, destroy, regenerate, bulk-regenerate)
- Galleries, Books, Chapters, Tags, Featured Items, Messages, Users

Tests use factories and verify HTTP responses and database state.

### `SecurityFixesTest.php`

Security-specific tests:
- Original artwork image access requires `can view source image` permission
- Admin-only routes are inaccessible to unauthenticated/non-admin users
- MarkdownRenderer strips HTML and unsafe links

### `DashboardTest.php`

Dashboard access tests.

### `PaginationStructureTest.php`

Verifies the pagination data shape in API responses.

### `SeoBuilderTest.php` (Unit)

Unit tests for `SeoBuilder`:
- Title formatting with/without brand append
- Description normalisation (markdown stripped, HTML stripped, length limited)
- Canonical URL query-string stripping

---

## Running Specific Tests

```bash
# All tests
php artisan test

# Single file
php artisan test --filter=PublicSiteTest

# Single test case
php artisan test --filter="can submit contact form"

# Feature tests only
php artisan test tests/Feature

# Unit tests only
php artisan test tests/Unit
```

---

## Known Gaps

| Gap | Tracking |
|---|---|
| No unit tests for jobs (`RegenerateArtworkImages`) | No task yet |
| No unit tests for queue/job dispatch or failure paths | No task yet |
| No CI test gate before deploy | TASK-004 |

---

## Factories

Model factories for all models are in `database/factories/`. Used extensively in feature tests.

---

## Related

- [[Architecture/Overview]]
- [[Infrastructure/Deployment]]
