# CONTEXT.md

Domain and architectural reference for mirispence.com. Read this before exploring the codebase for any non-trivial task — see `docs/agents/domain.md` for how agent skills consume it. High-level stack/commands live in `.claude/CLAUDE.md`; this file goes deeper on layout, domains, and operational detail.

---

## What This Is

mirispence.com — personal artist/writer portfolio site with a full admin CMS.
**Not** a SaaS, marketplace, or multi-tenant app. No public registration, no e-commerce/payments.

---

## Repository Layout

```
app/
  Console/Commands/     # CreateUser, GenerateSitemap, Media/Backfill
  Http/
    Controllers/
      Admin/            # Full CRUD for all content types
      Public/           # Read-only public-facing controllers
      Settings/         # Profile, password, 2FA
    Requests/Admin/     # Form Request validation classes
  Jobs/                 # RegenerateArtworkImages (queued)
  Models/               # Artwork, Gallery, Book, Chapter, Tag, FeaturedItem,
                        #   ContactMessage, CommissionRequest, User
  Providers/            # AppServiceProvider (Gate defs), FortifyServiceProvider,
                        #   HorizonServiceProvider
  Services/             # MarkdownRenderer
  Support/
    MediaLibrary/       # CustomPathGenerator
    Seo/                # SeoBuilder, SeoPayload
  Traits/               # HasUniqueSlug

resources/js/
  pages/                # Vue page components (Inertia pages)
  Layouts/              # PublicLayout, AdminLayout, AppLayout, AuthLayout, settings/Layout
  components/           # Shared Vue components (ui/*, ArtCard, SignedImage, etc.)
  actions/              # Auto-generated Wayfinder TypeScript actions (do not edit manually)
  composables/          # Vue composables
  app.ts                # Public + auth entry point
  admin.ts              # Admin panel entry point
  ssr.ts                # SSR entry point

routes/
  web.php               # Public routes + admin routes (role:admin guarded)
  settings.php          # /settings/* (auth guarded)
  console.php           # Scheduled commands
  ai.php                # MCP route (currently commented out)

database/
  migrations/           # All schema migrations
  factories/            # One factory per model
  seeders/              # DatabaseSeeder, RolesAndPermissionsSeeder

tests/
  Feature/
    Admin/              # CRUD tests for all admin resources
    Auth/                # Full Fortify auth flow tests
    Settings/            # Profile, password, 2FA settings tests
    PublicSiteTest.php   # All public routes
    SecurityFixesTest.php # XSS, admin gate, media permission, signed URL tests
    DashboardTest.php
  Unit/
    SeoBuilderTest.php

config/
  filesystems.php       # Defines r2_private, r2_public, media_private disks
  fortify.php            # Auth feature toggles
  horizon.php             # Queue config
  media-library.php       # Spatie Media Library config
  permission.php          # Spatie Permission config
```

---

## Domains

| Domain         | Models              | Routes                            | Admin CRUD              |
| -------------- | -------------------- | ---------------------------------- | ------------------------ |
| Artwork        | `Artwork`            | `/art`, `/art/{slug}`              | ✓                        |
| Gallery        | `Gallery`             | `/galleries`, `/galleries/{slug}`  | ✓                        |
| Book/Chapter   | `Book`, `Chapter`     | `/books`, `/books/{slug}`          | ✓                        |
| Tag            | `Tag`                 | —                                  | ✓                        |
| Featured Items | `FeaturedItem`        | —                                  | ✓                        |
| Contact        | `ContactMessage`      | `/contact`                         | read/update/delete only  |
| Commission     | `CommissionRequest`   | **none**                           | **none**                 |
| User           | `User`                | `/settings/*`                      | ✓                        |

---

## Key Models & Relationships

```
Artwork --< ArtworkGallery >-- Gallery   (pivot: sort_order)
Artwork --< ArtworkTag >-- Tag
Book --< Chapter
FeaturedItem (morph: artwork | book)
ContactMessage --< CommissionRequest    (commission is a sub-record of contact; no admin UI yet)
User (has roles/permissions via Spatie)
```

Artwork appends (computed attributes on every load): `media_urls`, `thumb_url`, `image_status_label`, `publish_status_label`, `description_html`

---

## Queue / Jobs

- `RegenerateArtworkImages` — tracks `image_status` on artwork (pending → processing → ready/failed)
- Dev: `QUEUE_CONNECTION=database` (run `php artisan queue:listen`)
- Prod: Redis + Horizon (`supervisorctl restart horizon` on deploy)

---

## Scheduler

- `sitemap:generate` runs daily (registered in `bootstrap/app.php` via `->withSchedule(...)`)

---

## CI / Deploy

- GitHub Actions: push to `main` triggers SSH deploy
- Deploy script: clone → composer install → npm ci + build → migrate → cache → reload fpm + horizon
- Rollback: re-symlinks previous timestamped release
- **No automated test run in CI pipeline** — see `.claude/CLAUDE.md` § Known incomplete areas

---

## Known issues

See `.claude/CLAUDE.md` § Known incomplete areas for the current list — kept in one place to avoid drift between docs.
