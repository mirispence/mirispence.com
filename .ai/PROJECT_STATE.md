# PROJECT_STATE.md

**Last updated:** 2026-03-02
**Phase:** implementation

---

## Goal

Personal artist/writer portfolio and CMS website (mirispence.com).
Publishes artwork, curated galleries, books/chapters, and handles contact/commission inquiries.
Includes a full admin panel for content management.
_Inferred from README, routes, and model structure. No goal document in `.ai/`._

## Non-goals

Not defined in `.ai/` files.
Inferred constraints: not multi-tenant, no public user registration, no e-commerce/payments.

---

## Stack (confirmed from manifests)

- **Framework:** Laravel 12 (`^12.0`)
- **PHP target:** 8.4 (pinned in `composer.json` platform config; deploy uses `php8.4-fpm`)
- **Frontend:** Vue 3 + TypeScript + Inertia.js 2 + Tailwind CSS v4 + Reka UI
- **Build:** Vite 7; two entry points — `app.ts` (public/auth) and `admin.ts` (admin panel); SSR entry `ssr.ts` also present
- **Test runner:** Pest 4 + pest-plugin-laravel 4 (PHPUnit underneath)
- **Queue:** Laravel Horizon (Redis in production; `database` driver in dev/`.env.example`)
- **Auth:** Laravel Fortify (login, register, password reset, email verification, TOTP 2FA)
- **Roles/permissions:** Spatie Laravel Permission v6
- **Media:** Spatie Media Library v11 (WebP conversions; R2 private + public buckets)
- **Storage:** Cloudflare R2 via S3 driver (`r2_private` for originals, `r2_public` for conversions)
- **Markdown:** `league/commonmark` v2 via `MarkdownRenderer` service (XSS-stripped)
- **Sitemap:** `spatie/laravel-sitemap` v7 (scheduled daily)
- **TypeScript actions:** Laravel Wayfinder (auto-generates typed TS action files in `resources/js/actions/`)
- **Deployment:** GitHub Actions → SSH → timestamped capistrano-style releases; rollback supported

---

## Domains / Modules

| Domain         | Models              | Routes                            | Admin CRUD              |
| -------------- | ------------------- | --------------------------------- | ----------------------- |
| Artwork        | `Artwork`           | `/art`, `/art/{slug}`             | ✓                       |
| Gallery        | `Gallery`           | `/galleries`, `/galleries/{slug}` | ✓                       |
| Book/Chapter   | `Book`, `Chapter`   | `/books`, `/books/{slug}`         | ✓                       |
| Tag            | `Tag`               | —                                 | ✓                       |
| Featured Items | `FeaturedItem`      | —                                 | ✓                       |
| Contact        | `ContactMessage`    | `/contact`                        | read/update/delete only |
| Commission     | `CommissionRequest` | **none**                          | **none**                |
| User           | `User`              | `/settings/*`                     | ✓                       |

---

## What Is Working (confirmed from code and tests)

- Public site: home, art index/show, galleries index/show, books index/show, contact form — all covered by `PublicSiteTest.php`
- Auth: full Fortify flow (login, register, password reset, email verification, 2FA) — all covered by `tests/Feature/Auth/`
- Admin panel: full CRUD for artworks, galleries, books, chapters, tags, featured items, messages, users — covered by `tests/Feature/Admin/`
- Slug uniqueness via `HasUniqueSlug` trait — collision test present
- Image pipeline: WebP conversions at 320w/640w/960w/1280w/1600w/2048w via Spatie Media Library; originals stored on R2 private, conversions on R2 public
- Queue-based image regeneration job (`RegenerateArtworkImages`) dispatched from admin; tracks `image_status` on model
- `Gate::before` grants admin role all permissions implicitly
- `view-original` Gate + `can view source image` permission protect original-image access — tested in `SecurityFixesTest`
- SEO support classes (`SeoBuilder`/`SeoPayload`) — unit tested
- MarkdownRenderer XSS stripping — tested in `SecurityFixesTest`
- Settings: profile edit, password update, 2FA management
- CI/CD deploy workflow with rollback
- **Pagination:** Fixed on Art Index, Gallery Index, and Gallery Show pages (TASK-007).

---

## What Is Broken or Missing

- **`SecurityFixesTest.php:80–98` — stale test:** `artwork model includes signed original url` references the removed `signed_urls` accessor; needs deletion (TASK-001).
- **`CommissionRequest` model/migration exists** with factory but has no controller, routes, or admin UI. Dead code / incomplete feature.
- **`.env.example` missing R2 env vars** (`R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_ENDPOINT`, `R2_PRIVATE_BUCKET`, `R2_PUBLIC_BUCKET`, `R2_PRIVATE_URL`, `R2_PUBLIC_URL`) despite being required by `config/filesystems.php`.
- **No test CI gate** in `deploy.yml` — tests are not run before deploying to production.
- **Admin forms restyle task pending** — `instructions/update-admin-styles.md` specifies a full admin form design system to implement; not yet applied (styles file exists at `resources/css/admin/forms.css` but admin pages not yet migrated).
- **`routes/ai.php`** MCP server route is commented out — incomplete/experimental.
- **No soft deletes** on any model — content is hard-deleted.
- **No Unit tests** for jobs, mail, or queue behavior; only `ExampleTest` and `SeoBuilderTest` in `tests/Unit/`.

---

## Key Decisions (confirmed from code)

- Inertia.js SPA over Blade for all views
- Vue 3 + TypeScript enforced (ESLint + Prettier configured)
- Spatie Media Library manages all media (not direct S3 uploads)
- Cloudflare R2 for storage (S3-compatible; private + public buckets)
- Laravel Fortify for auth (not Breeze/Jetstream)
- Spatie Permission for RBAC (admin role auto-granted all gates via `Gate::before`)
- Laravel Horizon for queue visibility (requires Redis in production)
- Slug uniqueness enforced via shared `HasUniqueSlug` trait
- Admin prefix `/admin` with `auth + verified + role:admin` middleware stack
- Original artwork images are private; permission `can view source image` required to access via signed admin route
- Image regeneration is async (queued job), not synchronous

---

## Next Milestone

---

## Notes

