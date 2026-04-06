# Technology Stack

Tags: #architecture #stack #dependencies

## Backend

| Layer | Technology | Version |
|---|---|---|
| Framework | Laravel | `^12.0` |
| PHP | PHP | `8.4` (pinned) |
| Auth | Laravel Fortify | — |
| RBAC | Spatie Laravel Permission | `v6` |
| Media | Spatie Media Library | `v11` |
| Markdown | `league/commonmark` (GFM) | `v2` |
| Sitemap | `spatie/laravel-sitemap` | `v7` |
| Queue UI | Laravel Horizon | — |

## Frontend

| Layer | Technology | Notes |
|---|---|---|
| SPA bridge | Inertia.js | `v2`, Vue adapter |
| Component framework | Vue 3 | Composition API + `<script setup>` |
| Language | TypeScript | Enforced via ESLint |
| Styling | Tailwind CSS | `v4` |
| Component library | Reka UI | — |
| Build tool | Vite | `v7` |
| Typed actions | Laravel Wayfinder | Auto-generates `resources/js/actions/` from routes |

## Storage

| Disk | Purpose | Driver |
|---|---|---|
| `r2_private` | Original uploaded files (artworks, book covers, gallery covers) | Cloudflare R2 (S3-compatible) |
| `r2_public` | WebP conversion outputs — served publicly via CDN | Cloudflare R2 |
| `local` | Laravel default private storage | Local filesystem |
| `public` | Laravel default public storage | Local filesystem |

## Testing

| Tool | Role |
|---|---|
| Pest 4 | Test runner (PHPUnit under the hood) |
| `pest-plugin-laravel` 4 | Laravel-specific test helpers |
| SQLite (in-memory) | Database for tests |

## Quality Tools

| Tool | Command | Notes |
|---|---|---|
| Laravel Pint | `./vendor/bin/pint` | Code style formatter |
| PHPStan | `./vendor/bin/phpstan` | Static analysis (level 5+) |
| ESLint | `npm run lint` | TypeScript/Vue linting |
| Prettier | via ESLint config | Formatting |

## CI/CD

GitHub Actions → SSH → capistrano-style timestamped releases on a Linux server running PHP 8.4-FPM + Supervisor (Horizon). See [[../Infrastructure/Deployment]].

## Related

- [[Overview]]
- [[../Infrastructure/Media Storage]]
- [[../Infrastructure/Queue & Horizon]]
- [[../Infrastructure/Deployment]]
