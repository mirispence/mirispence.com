# Artwork Domain

Tags: #domain #artwork #media

## Model: `App\Models\Artwork`

File: `app/Models/Artwork.php`

Implements `HasMedia` (Spatie). Uses traits: `HasFactory`, `InteractsWithMedia`, `HasUniqueSlug`.

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `title` | string | Slug source field |
| `slug` | string | Auto-generated from title |
| `description` | text | Markdown |
| `alt_text` | string | Accessibility |
| `created_on` | date | Artwork's real-world creation date |
| `publish_status` | string | `draft` \| `published` |
| `nsfw_flag` | boolean | |
| `featured_flag` | boolean | |
| `metadata` | json | Arbitrary metadata array |
| `image_status` | string | `pending` \| `processing` \| `ready` \| `failed` |
| `image_error` | text | Error message from failed regeneration |
| `image_processed_at` | datetime | When images were last regenerated |

### Computed Attributes (Appends)

| Attribute | Returns | Description |
|---|---|---|
| `media_urls` | array | All public CDN URL sets (original, thumb, grid, display) |
| `thumb_url` | ?string | 320w WebP thumbnail URL |
| `image_status_label` | string | Human-readable image status |
| `publish_status_label` | string | Ucfirst publish status |
| `description_html` | string | Markdown rendered to sanitised HTML |

### `media_urls` Shape

```json
{
  "original": "https://cdn.../img/{uuid}/artwork.jpg",
  "thumb":    "https://cdn.../img/{uuid}/conversions/thumb.webp",
  "grid": {
    "src":    "https://cdn.../grid_640.webp",
    "srcset": "https://cdn.../grid_640.webp 640w, https://cdn.../grid_960.webp 960w"
  },
  "display": {
    "src":    "https://cdn.../display_1280.webp",
    "srcset": "...1280w, ...1600w, ...2048w"
  }
}
```

> **Security note:** `PublicArtworkResource` strips the `original` URL — it is never sent to the public frontend.

### Media Collections & Conversions

Collection: `artwork` (single file, stored on `r2_private`)
Conversions stored on: `r2_public`

| Conversion name | Width | Format | Quality | Queued? |
|---|---|---|---|---|
| `thumb` | 320px | WebP | 80 | No (immediate) |
| `grid_640` | 640px | WebP | 80 | Yes |
| `grid_960` | 960px | WebP | 80 | Yes |
| `display_1280` | 1280px | WebP | 80 | Yes |
| `display_1600` | 1600px | WebP | 80 | Yes |
| `display_2048` | 2048px | WebP | 80 | Yes |

### Query Scopes

- `scopePublished($query)` — filters `publish_status = 'published'`
- `scopeFeatured($query)` — filters `featured_flag = true`

### Relationships

- `galleries()` — `BelongsToMany(Gallery)` with `sort_order` pivot
- `tags()` — `BelongsToMany(Tag)`

## Image Pipeline

When an artwork is created or its image is replaced in the admin panel:

1. File uploaded via `addMediaFromRequest('image')->toMediaCollection('artwork')`
2. Stored on `r2_private` under `img/{uuid}/`
3. `RegenerateArtworkImages::dispatch($artwork)` dispatched to queue
4. Job sets `image_status = processing`, calls Spatie's `FileManipulator::createDerivedFiles()`
5. On success: `image_status = ready`, `image_processed_at = now()`
6. On failure: `image_status = failed`, `image_error = exception message`

See [[../Backend/Jobs]] and [[../Infrastructure/Media Storage]].

## Admin Operations

- **Index** — paginated list (10/page) with galleries and tags loaded
- **Create** — upload image, set metadata, assign galleries/tags
- **Edit** — replace image, update metadata
- **Destroy** — hard delete (no soft deletes)
- **Regenerate** — single-artwork image re-conversion (permission: `can regenerate image thumbnails`)
- **Bulk Regenerate** — multiple artworks queued at once

## Public Operations

- **Index** (`/art`) — paginated (6/page), filterable by `tag` and `gallery` query params
- **Show** (`/art/{slug}`) — single artwork; 404s if not published

## Related

- [[../Architecture/Database Schema]]
- [[../Backend/Controllers - Public]]
- [[../Backend/Controllers - Admin]]
- [[../Backend/Jobs]]
- [[../Infrastructure/Media Storage]]
- [[../Infrastructure/Permissions & Roles]]
