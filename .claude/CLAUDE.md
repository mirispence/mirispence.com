# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

# Agent Protocol

## Required startup steps

Read:

- This file (`.claude/CLAUDE.md`)
- `CONTEXT.md` (domain/architecture reference)
- `docs/adr/` — ADRs relevant to the area you're about to work in

## Branching rules

- One branch per task.
- Suggested Branch naming: `ai/<agent-name>/<task-id>-<slug>`
- Never work directly on main/master.

## Collision avoidance

- Do not modify files already touched by another CLAIMED task.
- If unavoidable, stop and coordinate by updating TASKS.

## Allowed scope

- Work only within the claimed task scope.
- No opportunistic fixes or refactors.

## Completion requirements

Each completed task must provide:

- Summary: what changed and why
- Files changed: list
- How to test: exact commands
- Risks or follow-ups
- Updated `CONTEXT.md` / `docs/adr/` if the change affects domain layout or is a hard-to-reverse architectural decision (see `.claude/skills/update-project-documentation/SKILL.md`)

## Git rules

- Never merge into main/master without explicit instruction.
- Do not use `git push --force`, `git merge --squash`, `git rebase`, or `git commit --amend` unless explicitly instructed.
- No secrets in code, state files, logs, commits, or tests.
- Changes to logic, validation, orchestration, or security boundaries require tests.

## Laravel guardrails

- Follow Laravel conventions before inventing abstractions.
- Validation: HTTP via Form Requests (`app/Http/Requests/Admin/`); non-HTTP (jobs, imports, CLI) via dedicated validators or services.
- Database access: prefer Eloquent or query builder; raw SQL only when necessary, parameterized, and justified.
- Prefer dependency injection and testable services over static calls.
- HTTP behavior changes require Feature tests; pure logic changes require Unit tests; queue/job changes must test dispatch, payload shape, and failure paths.
- Do not migrate test frameworks (PHPUnit ↔ Pest) unless explicitly tasked.

---

# Development Commands

## Start dev environment (runs server + queue + Vite concurrently)

```bash
composer dev
```

## Run tests

```bash
composer test                         # clears config cache, then runs full suite
php artisan test                      # run full suite without clearing cache
php artisan test --filter=ArtworkTest # run a single test file
php artisan test tests/Feature/Admin/ArtworkTest.php  # explicit path
```

## Static analysis (PHPStan level 5 via Larastan — must pass at zero errors)

```bash
composer analyse
```

## Code formatting

```bash
./vendor/bin/pint                     # format PHP
npm run format                        # format JS/TS/Vue (Prettier)
```

## Frontend

```bash
npm run dev        # Vite dev server
npm run build      # production build (app.ts + admin.ts + ssr.ts)
npm run build:ssr  # SSR build
npm run type-check # TypeScript check
npm run lint       # ESLint
```

## Artisan helpers

```bash
php artisan queue:listen --tries=1    # run queue worker (dev)
php artisan make:user                 # create admin user (custom command)
php artisan sitemap:generate          # generate sitemap manually
```

---

# Architecture Overview

## What this is

Personal artist/writer portfolio + admin CMS. Single-tenant, no public registration, no e-commerce.

## Stack

| Layer         | Technology                                                                                    |
| ------------- | --------------------------------------------------------------------------------------------- |
| Framework     | Laravel 13, PHP 8.4                                                                           |
| Frontend      | Vue 3 + TypeScript + Inertia.js 2                                                             |
| CSS           | Tailwind v4 (config in CSS, no `tailwind.config.js`)                                          |
| UI primitives | Reka UI + shadcn-style components in `resources/js/components/ui/`                            |
| Build         | Vite 7; entries: `app.ts` (public/auth), `admin.ts` (admin), `ssr.ts` (SSR)                   |
| Auth          | Laravel Fortify (login, register, password reset, email verification, TOTP 2FA)               |
| RBAC          | Spatie Laravel Permission v8 — `admin` role auto-granted all gates via `Gate::before`         |
| Media         | Spatie Media Library v11; originals on R2 private, WebP conversions on R2 public              |
| Storage       | Cloudflare R2 via S3 driver (`r2_private`, `r2_public`, `media_private` disks)                |
| Queue         | Laravel Horizon (Redis prod; `database` driver dev)                                           |
| Routing types | Laravel Wayfinder — auto-generates typed TS in `resources/js/actions/` (do not edit manually) |
| Markdown      | `league/commonmark` v2 via `App\Services\MarkdownRenderer` (XSS-stripped)                     |
| Tests         | Pest 4 + pest-plugin-laravel 4                                                                |

## Request flow

All routes return Inertia responses — no Blade page templates. Pages live in `resources/js/pages/`. Controllers share props via `Inertia::render()`.

## Auth & permissions

- Admin middleware stack: `auth`, `verified`, `role:admin`
- `Gate::before` returns `true` for any user with the `admin` role (bypasses all individual permission checks)
- Permissions: `can view source image`, `can regenerate image thumbnails`
- `view-original` Gate protects original image access at `GET admin/media/{media}/original`
- Middleware aliases registered in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`

## Media pipeline

- Upload → Spatie Media Library → stored on `r2_private`
- Conversions (thumb, grid_640, grid_960, display_1280/1600/2048) → WebP on `r2_public`
- `thumb` is synchronous; all others are async via `RegenerateArtworkImages` job
- `image_status` on `Artwork` model tracks job state (pending → processing → ready/failed)
- **Tests fake `media_private` and `public` disks**, NOT `r2_private`/`r2_public` — use `Storage::fake('media_private')` and `Storage::fake('public')`

## Models & key relationships

```
Artwork --< ArtworkGallery >-- Gallery   (pivot: sort_order)
Artwork --< ArtworkTag >-- Tag
Book --< Chapter
FeaturedItem (morph: artwork | book)
ContactMessage (commission sub-record exists but has no routes/UI)
User (roles/permissions via Spatie)
```

Artwork appends computed attributes on every load: `media_urls`, `thumb_url`, `image_status_label`, `publish_status_label`, `description_html`.

## Validation

All HTTP validation uses Form Request classes in `app/Http/Requests/Admin/`. Do not put validation rules directly in controllers.

## Frontend conventions

- Use Wayfinder typed actions (`resources/js/actions/`) instead of hardcoded URL strings
- `SignedImage.vue` component for serving protected/private images
- `components/ui/` for shared primitives (Button, Card, Badge, etc.)
- Tailwind v4: all config is in CSS files, not a JS config file

## Key config files

- `config/filesystems.php` — defines the three R2 disks
- `config/fortify.php` — auth feature toggles
- `config/media-library.php` — Spatie Media Library settings
- `phpstan.neon` — PHPStan config (level 5, Larastan)

## Known incomplete areas

- `CommissionRequest` model/migration/factory exists but has no routes or admin UI
- `.env.example` is missing all R2 environment variables
- Admin form restyle is specced in `instructions/update-admin-styles.md` but not yet applied
- No automated test run in the CI/CD pipeline before deploy

---

## Agent skills

### Issue tracker

Issues live in GitHub Issues (`mirispence/mirispence.com`), managed via the `gh` CLI. See `docs/agents/issue-tracker.md`.

### Triage labels

Default canonical labels (needs-triage, needs-info, ready-for-agent, ready-for-human, wontfix). See `docs/agents/triage-labels.md`.

### Domain docs

Single-context — `CONTEXT.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.
