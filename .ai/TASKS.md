# TASKS.md

## Active

_none_

## Done

### TASK-006 · Add pagination to gallery show page; reduce art index to 6 per page

- **Status:** DONE
- **Branch:** ai/claude/task-006-gallery-pagination
- **Scope:** `app/Http/Controllers/Public/GalleryController.php`, `app/Http/Controllers/Public/ArtworkController.php`, `resources/js/pages/Public/Galleries/Show.vue`, `tests/Feature/PublicSiteTest.php`, `app/Support/Seo/SeoBuilder.php` (bug fix: missing `use App\Models\Gallery` import)
- **Summary:** `/galleries/{slug}` show page now queries artworks separately and paginates at 6/page, passing artworks as a dedicated Inertia prop with `data`/`links`/`meta`. Art index reduced from 12 to 6/page. Gallery Show Vue updated to use `artworks.data`, empty state, and pagination nav. Fixed pre-existing missing Gallery import in SeoBuilder that was causing 500 on gallery show.
- **How to test:** `php artisan test --filter=PublicSiteTest` — 10 tests, 119 assertions, all pass.
- **Risks:** None.

## Backlog

### TASK-001 · Remove broken signed_urls test from SecurityFixesTest

- **Status:** BACKLOG
- **Branch:** —
- **Scope:** `tests/Feature/SecurityFixesTest.php`
- **Summary:** Signed URLs have been removed from the project. The test `artwork model includes signed original url` references the non-existent `$artwork->signed_urls` accessor and will throw `TypeError` in PHP 8.4. Delete that test case entirely.
- **How to test:** `php artisan test --filter=SecurityFixesTest` — all remaining cases should pass.
- **Risks:** None. Removing a test for a removed feature.

---

### TASK-002 · Admin form restyle

- **Status:** BACKLOG
- **Branch:** —
- **Scope:** `resources/css/admin/forms.css`, `resources/js/Pages/Admin/**/*.vue`
- **Summary:** Implement the shared admin form design system defined in `instructions/update-admin-styles.md`. Apply `.admin-page`, `.admin-card`, `.form-row`, `.control`, `.actions-bar`, and related classes across all admin form pages. Purely presentational — no behavior changes.
- **How to test:** Visual review of all admin create/edit pages. Run `php artisan test` to confirm no regressions.
- **Risks:** Low. No logic changes. Watch for missed pages or broken slot/prop bindings after markup restructure.

---

### TASK-003 · Commission request admin UI

- **Status:** BACKLOG
- **Branch:** —
- **Scope:** `app/Http/Controllers/Admin/CommissionRequestController.php` (new), `routes/web.php`, `resources/js/Pages/Admin/CommissionRequests/` (new)
- **Summary:** `CommissionRequest` model, migration, and factory exist but have no admin interface. Add a read/update admin controller (index + show + update status/quote) and wire up routes under `/admin/commission-requests`. Link from the related `ContactMessage` show page.
- **How to test:** `php artisan test --filter=CommissionRequest` (new tests required per GUARDRAILS)
- **Risks:** Clarify whether commission requests are created manually by admin or submitted via a public form (no public route exists yet).

---

### TASK-004 · Add test CI gate to deploy workflow

- **Status:** BACKLOG
- **Branch:** —
- **Scope:** `.github/workflows/deploy.yml`
- **Summary:** The current deploy workflow runs no tests before deploying. Add a `test` job that runs `composer test` (SQLite in-memory) and make the `deploy` job depend on it passing.
- **How to test:** Push a branch with a known failing test and confirm the deploy job does not run.
- **Risks:** Will add ~1–2 min to deploy time. Ensure the CI runner has PHP 8.4 and required extensions (pcntl, posix may need to be handled).

---

# TASK-005 - Resolve PHPStan level 5 errors (36 errors on first run)

## Done

_none_
