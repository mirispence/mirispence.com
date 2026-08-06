# ADR-0001: Foundational stack decisions

## Status

Accepted (retroactively documented — these were project-inception choices, not a single decision point).

## Context

Early architectural choices for mirispence.com that shape how features get built. Recorded here so later agents don't second-guess or silently override them.

## Decisions

- **Inertia.js SPA over Blade** for all views — no server-rendered page templates.
- **Vue 3 + TypeScript enforced** (ESLint + Prettier configured) — no plain JS.
- **Spatie Media Library manages all media**, not direct S3/R2 uploads — conversions, path generation, and disk routing all go through it.
- **Cloudflare R2 for storage** (S3-compatible) — `r2_private` for originals, `r2_public` for conversions, kept as separate disks rather than one bucket with ACLs.
- **Laravel Fortify for auth**, not Breeze or Jetstream — headless, paired with custom Inertia/Vue views.
- **Spatie Permission for RBAC** — `admin` role auto-granted all gates via `Gate::before`, rather than per-permission checks everywhere.
- **Laravel Horizon for queue visibility** — requires Redis in production; `database` driver in dev.
- **Slug uniqueness enforced via a shared `HasUniqueSlug` trait**, not per-model logic.
- **Admin routes under `/admin` prefix** with `auth + verified + role:admin` middleware stack.
- **Original artwork images are private** — `can view source image` permission required to access via signed admin route (`GET admin/media/{media}/original`).
- **Image regeneration is async** (queued `RegenerateArtworkImages` job), not synchronous — except the `thumb` conversion, which stays sync for immediate feedback.

## Consequences

- New content types should follow the Inertia + Form Request + Eloquent pattern already established — see `CONTEXT.md` and `.claude/CLAUDE.md`.
- Any deviation from these (e.g. adding a Blade view, bypassing Media Library for uploads, switching auth packages) is a significant enough change to warrant its own ADR before implementation.
