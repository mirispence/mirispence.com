# ADR-0005: `/art` is for Galleries, `/artwork` is for Artworks

## Status

Accepted (2026-10-25). Implementation is tracked in the T5 issue of the public-site design round.

## Context

The public site had `/art` (every artwork, headed "Gallery"), `/art/{slug}` (artwork detail) and `/galleries` (headed "Collections", linked from nowhere). The owner wants `/art` to be the base for browsing art, with galleries under it, and "Collection" dropped as a term.

Artwork and Gallery slugs are unique only within their own tables, and an Artwork can belong to several Galleries or none. Putting both under `/art/{slug}` would make them collide.

## Decision

| URL | Page |
| --- | --- |
| `/art` | Gallery list |
| `/art/{gallery:slug}` | A Gallery and its Artworks |
| `/artwork` | Every published Artwork, filterable by `?tag=` |
| `/artwork/{artwork:slug}` | The Artwork's single canonical detail page, linking back to every Gallery it belongs to |

The `/galleries` routes are removed. **No 301 redirects** from the old URLs: this was the owner's decision.

## Considered options

- **`/art/{gallery}/{artwork}`.** Rejected: an Artwork in several Galleries, or in none, has no single canonical URL.
- **One shared `/art/{slug}` namespace.** Rejected: it needs a slug uniqueness rule across two tables and is fragile.

## Consequences

- Old `/art/{slug}` and `/galleries/*` links (bookmarks, search results, social posts) will 404 once this ships.
- The sitemap, canonical URLs and tag links all move to the new scheme.
