# Gallery Domain

Tags: #domain #gallery #media

## Model: `App\Models\Gallery`

File: `app/Models/Gallery.php`

Implements `HasMedia`. Uses traits: `HasFactory`, `InteractsWithMedia`, `HasUniqueSlug`.

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `name` | string | Slug source field |
| `slug` | string | Auto-generated from name |
| `description` | text | Markdown |
| `sort_order` | integer | Ordering on the galleries index page |
| `publish_status` | string | `draft` \| `published` |

### Computed Attributes (Appends)

| Attribute | Returns | Description |
|---|---|---|
| `media_urls` | array | `{ original, thumb }` |
| `image_url` | string | Original cover image URL |
| `thumb_url` | ?string | 320w WebP thumbnail URL |
| `description_html` | string | Markdown rendered to HTML |

### Media Collections & Conversions

Collection: `gallery` (single cover image, stored on `r2_private`)
Conversions stored on: `r2_public`

| Conversion | Width | Format | Quality | Queued? |
|---|---|---|---|---|
| `thumb` | 320px | WebP | 80 | No |

### Relationships

- `artworks()` — `BelongsToMany(Artwork)` with `sort_order` pivot and timestamps

### Query Scopes

- `scopePublished($query)` — filters `publish_status = 'published'`

## Public Behaviour

- **Index** (`/galleries`) — paginated (6/page), ordered by `sort_order`
- **Show** (`/galleries/{slug}`) — 404 if not published; artworks within the gallery paginated (6/page), ordered by `pivot_sort_order`

Artworks in a gallery are filtered to `publish_status = published` and ordered by their `sort_order` pivot value.

## Admin Operations

Full CRUD. Sort order is managed manually through the admin edit form.

## Related

- [[Artwork]]
- [[../Architecture/Database Schema]]
- [[../Backend/Controllers - Public]]
- [[../Backend/Controllers - Admin]]
