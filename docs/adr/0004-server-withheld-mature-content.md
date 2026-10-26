# ADR-0004: Mature content is withheld by the server until consent

## Status

Accepted (2026-10-25). Implementation is tracked in the T1 issue of the public-site design round.

## Context

Artworks flagged `nsfw_flag` were "gated" only in the browser. `PublicArtworkResource` sent full `grid`/`display` URLs for every artwork, and the card blurred the real image with CSS. The lightbox's next/prev skipped the gate entirely, and the home hero rendered featured artwork with no gate. Conversions live on the world-readable `r2_public` bucket (ADR-0003), so anyone reading the page props could open a flagged image without consenting.

## Decision

- **No media URLs for flagged artwork until the session has consented.** For a flagged artwork the public resource omits `media_urls` (cards show a neutral placeholder, not a blurred real image), unless that artwork's ID is in the session's revealed set or the session's "show all" flag is set.
- **Consent is stored in the Laravel session**, as a set of revealed artwork IDs plus a boolean "show all" flag. It inherits the session lifetime (`SESSION_LIFETIME`, 120 minutes idle). There is no separate cookie and no long-lived consent.
- **Revealing goes through the server.** "Reveal" (one artwork) and "Show all this session" call a consent endpoint, then the page reloads the affected props. This works the same on cards, the detail page and inside the lightbox.
- **Flagged artwork never feeds SEO or the home hero.** `og:image`, `twitter:image` and JSON-LD `image` fall back to the site default for flagged artwork. Hero selection excludes flagged artwork on the server.

## Considered options

- **Client-side gate, made consistent.** Rejected: the URLs stay in the page source, so the gate is cosmetic.
- **Server-withheld plus a blurred low-res conversion.** Rejected for now: it needs a new media conversion and a backfill of existing images. It can be added later without changing this boundary.

## Consequences

- Any new public serialization of Artwork must go through `PublicArtworkResource` (or apply the same consent check), or it reopens the leak. Tests assert that flagged artwork props carry no media URLs before consent.
- Once a URL has been revealed it is a plain public R2 URL and can be shared. This ADR controls disclosure in page props; it does not control the bucket (see ADR-0003).
- Responses that include flagged artwork vary by session, which matters if full-page HTTP caching is ever added.
