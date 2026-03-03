# CONTEXT.md

Quick architectural reference for AI agents working on this codebase.

---

## What This Is

mirispence.com — personal artist/writer portfolio site with a full admin CMS.
**Not** a SaaS, marketplace, or multi-tenant app.

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
    Auth/               # Full Fortify auth flow tests
    Settings/           # Profile, password, 2FA settings tests
    PublicSiteTest.php  # All public routes
    SecurityFixesTest.php  # XSS, admin gate, media permission, signed URL tests
    DashboardTest.php
  Unit/
    SeoBuilderTest.php

config/
  filesystems.php       # Defines r2_private, r2_public, media_private disks
  fortify.php           # Auth feature toggles
  horizon.php           # Queue config
  media-library.php     # Spatie Media Library config
  permission.php        # Spatie Permission config
```

---

## Auth & Permissions

- **Auth:** Laravel Fortify. Routes registered automatically. Views are Inertia/Vue.
- **Roles:** `admin` (only role currently seeded via `RolesAndPermissionsSeeder`)
- **Permissions:** `can view source image`, `can regenerate image thumbnails`
- **Gate:** `view-original` gate maps to `can view source image` permission
- **Admin auto-grant:** `Gate::before` returns `true` for any user with `admin` role

Middleware aliases registered in `bootstrap/app.php`:
- `role` → `RoleMiddleware`
- `permission` → `PermissionMiddleware`
- `role_or_permission` → `RoleOrPermissionMiddleware`

---

## Storage / Media

- **Original artwork images** → `r2_private` disk (Cloudflare R2, private)
- **Image conversions** (thumb, grid_640, grid_960, display_1280/1600/2048) → `r2_public` disk (Cloudflare R2, public CDN)
- All conversions are WebP format
- `thumb` conversion is **non-queued** (sync); all others are async
- Admin can regenerate via `POST admin/artworks/{artwork}/regenerate` or bulk via `POST admin/artworks/bulk-regenerate`
- Original access requires `can view source image` permission via `GET admin/media/{media}/original`

**Test note:** Tests fake `media_private` and `public` disks, NOT `r2_private`/`r2_public`. Media tests use `UploadedFile::fake()`.

---

## Frontend Conventions

- All routes use **Inertia.js** (no blade templates for page content)
- Use **Laravel Wayfinder** typed actions instead of hardcoded URLs — import from `resources/js/actions/`
- `components/ui/` contains Reka UI + shadcn-style primitives (Button, Card, Badge, etc.)
- `SignedImage.vue` component for serving protected images
- Tailwind v4 (no `tailwind.config.js` — config is in CSS)
- Admin has a pending form design system spec in `instructions/update-admin-styles.md`

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
- **No automated test run in CI pipeline**

---

## Known Issues (as of 2026-03-02)

1. `SecurityFixesTest::artwork model includes signed original url` — references non-existent `signed_urls` accessor (model has `media_urls`); likely a broken test from a refactor
2. `CommissionRequest` model/migration/factory exists but has no routes or admin UI
3. `.env.example` is missing all R2 environment variables
4. Admin form restyle (`instructions/update-admin-styles.md`) is specced but not yet implemented
