# ADR-0003: Original-image access and Book/Gallery covers

## Status

Accepted. Partially supersedes ADR-0001 (the "access via signed admin route" wording for originals).

## Context

Spec issue #1 (tickets #2–#5) centralised media URL building in `App\Support\Media\MediaUrlBuilder` and stopped leaking private original URLs into Inertia props. As part of that work, Book and Gallery stopped owning their own media and now borrow covers from Artworks. Several of these choices are hard to reverse or easy to undo by accident, so they are recorded here.

## Decisions

- **Originals are served by redirecting to a short-lived presigned R2 URL.** `GET admin/media/{media}/original` (`admin.media.original`, gated by `can view source image` / the admin role) redirects to `Media::getTemporaryUrl()`, with a lifetime of `media-library.temporary_url_default_lifetime` minutes. The app never streams the file and never signs its own route. This supersedes ADR-0001's "access via signed admin route" wording: the admin route is protected by auth and permission middleware, and the signature lives on the R2 URL. No serialized payload (public or admin) contains an `original` key or a raw original URL. The admin artwork edit page receives only `originalMediaId`.
- **Book and Gallery no longer own media.** Neither model is `HasMedia`. `Gallery.cover_artwork_id -> Artwork` (nullable, `nullOnDelete`) picks a member Artwork as the cover, and `Book.gallery_id -> Gallery` (nullable, `nullOnDelete`) borrows that Gallery's cover. Old Book/Gallery media is **not backfilled**: there is no production data to migrate.
- **A gallery cover is set only on edit.** On create, `cover_artwork_id` is `prohibited` because a new gallery has no members yet. On update, the cover must be a current member. When an artwork leaves a gallery (`Artwork::syncGalleries()`), any gallery that used it as its cover has the cover cleared. `cover_urls` also ignores a cover that is no longer a member, as a safety net for membership changes made outside that path.
- **An unpublished cover or gallery yields no public cover.** `Gallery::cover_urls` is `[]` when the cover Artwork is not published. `Book::cover` is `null` when the Gallery is unpublished or has no visible cover. Admin thumbnails (`cover_thumb_url`) still show draft covers.
- **The R2 public bucket's visibility is an accepted, out-of-scope risk.** Conversions on `r2_public` are world-readable by design. Whether the `r2_private` bucket or its keys could be exposed is an infrastructure concern and is not addressed by this change.

## Consequences

- To use a cover, eager-load it with the `withPublicCover()` scope on Gallery or Book (or `Book::loadPublicCover()` for a single model), rather than appending `cover_urls`/`cover` ad hoc.
- Any new code that changes gallery membership must go through `Artwork::syncGalleries()`, or clear stale covers itself.
- Re-introducing an `original`/`image_url` field on any serialized model breaks the `assertNoOriginalMedia()` test guards.
