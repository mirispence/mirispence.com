# Architecture Overview

Tags: #architecture #overview

## Purpose

mirispence.com is the personal portfolio and CMS site for artist/author Miri Spence. It serves two distinct audiences:

- **Public visitors** — browse artwork, galleries, books, and send contact/commission inquiries
- **Admin users** — manage all content through a full back-office panel

## Non-Goals

- Not multi-tenant
- No public user registration
- No e-commerce / payment processing
- No social / follower features

## Architectural Style

**Monolith** — single Laravel application serving both the public site and the admin panel. Inertia.js bridges server-side Laravel controllers to a Vue 3 SPA front-end without a separate API layer.

## Key Decisions

| Decision                                               | Rationale                                                                                                   |
| ------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------- |
| Inertia.js instead of a REST API                       | Keeps front-end/back-end collocated; no auth token plumbing; shares Laravel validation errors automatically |
| Two separate Vite entry points (`app.ts` / `admin.ts`) | Admin bundle is never loaded by public visitors; allows different component scopes                          |
| Spatie Media Library for all uploads                   | Handles R2 upload, conversion queuing, and URL generation uniformly                                         |
| Cloudflare R2 — two buckets                            | Originals protected in `r2_private`; public WebP conversions served from `r2_public` CDN                    |
| Laravel Fortify for auth                               | Headless; pairs cleanly with Inertia/Vue without pulling in opinionated views                               |
| Spatie Permission for RBAC                             | `Gate::before` grants the `admin` role all gates implicitly — no per-action permission boilerplate          |
| `HasUniqueSlug` trait                                  | Centralises slug uniqueness logic across Artwork, Gallery, and Book                                         |
| No soft deletes                                        | Content is hard-deleted; no recycle bin feature                                                             |

## Phase / Status

The project is in the **implementation** phase. Core features (art, galleries, books, contact, admin CRUD, auth) are working and covered by feature tests. See [[../Architecture/Overview|this file]] and `.ai/PROJECT_STATE.md` for known gaps.

## Related

- [[Stack]]
- [[Directory Structure]]
- [[Database Schema]]
- [[../Infrastructure/Permissions & Roles]]
