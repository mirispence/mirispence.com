# Directory Structure

Tags: #architecture #files

```
mirispence.com/
├── .ai/                          # Agent protocol files (PROJECT_STATE, TASKS, GUARDRAILS)
├── .github/
│   └── workflows/
│       └── deploy.yml            # GitHub Actions deploy + rollback workflow
├── app/
│   ├── Actions/
│   │   └── Fortify/              # Fortify action classes (CreateNewUser, ResetUserPassword, etc.)
│   ├── Console/                  # Artisan commands (none custom currently)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin CRUD controllers
│   │   │   ├── Public/           # Public site controllers
│   │   │   └── Settings/         # Auth settings controllers
│   │   ├── Middleware/           # Laravel default middleware
│   │   ├── Requests/
│   │   │   ├── ContactRequest.php
│   │   │   └── Settings/         # Profile/password form requests
│   │   └── Resources/
│   │       └── PublicArtworkResource.php  # JSON resource — only exposes public CDN URLs
│   ├── Jobs/
│   │   └── RegenerateArtworkImages.php    # Queued image conversion job
│   ├── Models/
│   │   ├── Artwork.php
│   │   ├── Book.php
│   │   ├── Chapter.php
│   │   ├── CommissionRequest.php   # Exists but has no controller/routes (incomplete)
│   │   ├── ContactMessage.php
│   │   ├── FeaturedItem.php
│   │   ├── Gallery.php
│   │   ├── Tag.php
│   │   └── User.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php  # Gate::before, view-original gate, morphMap
│   │   ├── FortifyServiceProvider.php
│   │   └── HorizonServiceProvider.php
│   ├── Services/
│   │   └── MarkdownRenderer.php    # GFM converter, XSS-stripped
│   ├── Support/
│   │   ├── MediaLibrary/
│   │   │   └── CustomPathGenerator.php  # img/{uuid}/conversions/ path scheme
│   │   └── Seo/
│   │       ├── SeoBuilder.php      # Static factory — builds SeoPayload per page type
│   │       └── SeoPayload.php      # DTO for SEO meta, OG, Twitter, JSON-LD
│   └── Traits/
│       └── HasUniqueSlug.php       # Auto-generates unique slugs on create/update
├── bootstrap/                      # Laravel bootstrap files
├── config/
│   ├── filesystems.php             # r2_private + r2_public disks
│   ├── fortify.php                 # Fortify feature flags
│   ├── horizon.php                 # Horizon environment config
│   ├── media-library.php           # Media Library config (custom path generator)
│   └── permission.php              # Spatie Permission config
├── database/
│   ├── factories/                  # Model factories for testing
│   ├── migrations/                 # All schema migrations
│   └── seeders/                    # (minimal, no default seeders)
├── instructions/
│   └── update-admin-styles.md      # Spec for TASK-002 admin form restyle
├── public/                         # Web root
├── resources/
│   ├── css/
│   │   ├── app.css                 # Public site styles (Tailwind)
│   │   ├── artwork.css             # Artwork-specific CSS
│   │   └── admin/
│   │       └── forms.css           # Admin form design system (pending TASK-002)
│   ├── js/
│   │   ├── app.ts                  # Public + auth Inertia entry point
│   │   ├── admin.ts                # Admin Inertia entry point
│   │   ├── ssr.ts                  # SSR entry point
│   │   ├── actions/                # Wayfinder-generated typed route helpers
│   │   ├── components/             # Shared Vue components
│   │   ├── composables/            # Vue composables
│   │   ├── layouts/                # AppLayout, AdminLayout, AuthLayout, PublicLayout
│   │   ├── lib/                    # Utility functions
│   │   ├── pages/
│   │   │   ├── Admin/              # Admin panel pages
│   │   │   ├── Public/             # Public site pages
│   │   │   ├── auth/               # Login, register, password reset pages
│   │   │   └── settings/           # Profile, password, 2FA settings pages
│   │   ├── routes/                 # Client-side route helpers
│   │   ├── types/                  # TypeScript type definitions
│   │   └── wayfinder/              # Wayfinder runtime
│   ├── svg/                        # SVG icons (copied to public/icons by Vite)
│   └── views/                      # Blade views (only app.blade.php root)
├── routes/
│   ├── web.php                     # Public routes + admin route group
│   ├── settings.php                # Auth settings routes
│   ├── console.php                 # Artisan schedule
│   └── ai.php                      # Commented-out MCP server route (experimental)
├── tests/
│   ├── Feature/
│   │   ├── Admin/                  # Admin CRUD feature tests
│   │   ├── Auth/                   # Fortify auth flow tests
│   │   ├── Settings/               # Settings feature tests
│   │   ├── PublicSiteTest.php
│   │   ├── SecurityFixesTest.php
│   │   ├── DashboardTest.php
│   │   └── PaginationStructureTest.php
│   └── Unit/
│       ├── ExampleTest.php
│       └── SeoBuilderTest.php
├── vault/
│   └── documentation/              # This vault
├── composer.json
├── package.json
├── phpstan.neon
├── tsconfig.json
└── vite.config.ts
```

## Related

- [[Stack]]
- [[../Backend/Controllers - Admin]]
- [[../Backend/Controllers - Public]]
- [[../Frontend/Entry Points]]
