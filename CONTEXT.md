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
    Media/              # MediaUrlBuilder (single source of media URL sets)
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
    PublicSiteTest.php   # All public routes, gallery/book covers, withPublicCover query count
    PublicOriginalMediaLeakTest.php # No `original` key / original URL in public props or seo
    ArtworkMediaUrlsTest.php # Artwork media_urls/thumb_url never expose the original
    SecurityFixesTest.php # XSS, admin gate, media permission, presigned original URL tests
    DashboardTest.php
  Pest.php               # assertNoOriginalMedia() recursive leak helper
  Unit/
    SeoBuilderTest.php
    MediaUrlBuilderTest.php
    MarkdownRendererTest.php # Raw HTML / on* handlers stripped, unsafe link schemes neutralised

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
Gallery >-- Artwork                      (cover_artwork_id, nullable, nullOnDelete: explicit cover)
Artwork --< ArtworkTag >-- Tag
Book --< Chapter
Book >-- Gallery                         (gallery_id, nullable, nullOnDelete: cover source)
FeaturedItem (morph: artwork | book)
ContactMessage --< CommissionRequest    (commission is a sub-record of contact; no admin UI yet)
User (has roles/permissions via Spatie)
```

Artwork appends (computed attributes on every load): `media_urls`, `thumb_url`, `image_status_label`, `publish_status_label`, `description_html`

Media URL sets (`media_urls`, `thumb_url`, `PublicArtworkResource`) are built only by `App\Support\Media\MediaUrlBuilder` (`public()` / `admin()`), which never exposes an `original` key. The raw `media` relation is hidden from Artwork serialization (Spatie's `Media::toArray()` includes `original_url`). Originals are reachable only via the gated `admin.media.original` route.

Gallery has no media of its own (not `HasMedia`). Its cover is an explicit pick of one of its own member Artworks (`Gallery::coverArtwork()`, column `cover_artwork_id`), independent of the pivot `sort_order`. `cover_urls` (= cover Artwork's `MediaUrlBuilder::public()`, `[]` if no cover, the cover isn't published, or it is no longer a member) is not auto-appended: query with the `withPublicCover()` scope (eager-loads the cover chain + membership flag and appends it). The cover is chosen on gallery edit (validated as a member); create cannot set one because a new gallery has no members. `Artwork::syncGalleries()` clears the cover of any gallery the artwork leaves. See ADR-0003.

Book has no media of its own either (not `HasMedia`). It points at a Gallery (`Book::gallery()`, column `gallery_id`, picked from all galleries on admin create/edit), and its cover is that Gallery's cover Artwork. The `gallery` relation is hidden. The `cover` accessor (not auto-appended) returns `{urls: <gallery cover_urls>, gallery: {name, slug}}`, or `null` when there's no gallery, the gallery isn't published, or the gallery has no visible cover. Use the `withPublicCover()` scope (or `$book->loadPublicCover()` for one model). The public Book show hero renders `cover.urls.display` and links to `galleries.show`. With no cover, no hero is rendered. Admin books index rows carry `cover_thumb_url`.

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

- `ci.yml`: on PRs to `main`, runs `composer test`, `composer analyse` (PHPStan), `npx eslint .`
- `deploy.yml`: push to `main` triggers SSH deploy directly — does not re-run the CI checks
- Deploy script: clone → composer install → npm ci + build → migrate → cache → reload fpm + horizon
- Rollback: re-symlinks previous timestamped release

---

## Known issues

See `.claude/CLAUDE.md` § Known incomplete areas for the current list — kept in one place to avoid drift between docs.
