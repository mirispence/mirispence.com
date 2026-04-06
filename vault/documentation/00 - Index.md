# mirispence.com — Documentation Vault

> Personal portfolio and CMS for artist/author Miri Spence. Publishes artwork, curated galleries, books/chapters, and handles contact/commission inquiries via a full admin panel.

## Quick Navigation

### Architecture
- [[Architecture/Overview]] — Goals, constraints, key decisions
- [[Architecture/Stack]] — Full technology inventory
- [[Architecture/Directory Structure]] — File-system map
- [[Architecture/Database Schema]] — Tables, columns, relationships

### Domains
- [[Domains/Artwork]] — Artwork model, image pipeline, media conversions
- [[Domains/Gallery]] — Gallery model, artwork membership
- [[Domains/Book & Chapter]] — Book model, chapter ordering, markdown
- [[Domains/Tag]] — Tagging system, types
- [[Domains/Featured Items]] — Polymorphic featured content
- [[Domains/Contact & Commission]] — Contact form, commission flow, rate limiting
- [[Domains/User & Auth]] — User model, Fortify auth, 2FA, settings

### Backend
- [[Backend/Controllers - Public]] — Public-facing HTTP controllers
- [[Backend/Controllers - Admin]] — Admin CRUD controllers
- [[Backend/Controllers - Settings]] — Profile/password/2FA settings
- [[Backend/Services]] — MarkdownRenderer
- [[Backend/Jobs]] — RegenerateArtworkImages queue job
- [[Backend/Traits]] — HasUniqueSlug

### Frontend
- [[Frontend/Entry Points]] — app.ts, admin.ts, ssr.ts
- [[Frontend/Pages - Public]] — Public Vue pages
- [[Frontend/Pages - Admin]] — Admin Vue pages
- [[Frontend/Layouts]] — AppLayout, AdminLayout, AuthLayout, PublicLayout
- [[Frontend/Components]] — Shared component library

### Infrastructure
- [[Infrastructure/Media Storage]] — Cloudflare R2, Spatie Media Library, CustomPathGenerator
- [[Infrastructure/Auth - Fortify]] — Fortify configuration and flow
- [[Infrastructure/Permissions & Roles]] — Spatie Permission, Gate::before
- [[Infrastructure/SEO]] — SeoBuilder, SeoPayload, JSON-LD
- [[Infrastructure/Queue & Horizon]] — Laravel Horizon, queue jobs
- [[Infrastructure/Deployment]] — GitHub Actions, capistrano-style releases, rollback

### Routing
- [[Routing/Routes]] — All routes, middleware, named routes

### Testing
- [[Testing]] — Test suite overview, coverage, known issues
