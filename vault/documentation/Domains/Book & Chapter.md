# Book & Chapter Domain

Tags: #domain #book #chapter #markdown

## Model: `App\Models\Book`

File: `app/Models/Book.php`

Implements `HasMedia`. Uses traits: `HasFactory`, `InteractsWithMedia`, `HasUniqueSlug`.

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `title` | string | Slug source field |
| `slug` | string | Auto-generated |
| `description` | text | Markdown |
| `publish_status` | string | `draft` \| `published` |
| `featured_flag` | boolean | |
| `release_date` | date | |
| `external_links` | json | Array of link objects (e.g. Amazon, Goodreads) |

### Computed Attributes (Appends)

| Attribute | Returns |
|---|---|
| `media_urls` | `{ original, thumb }` |
| `image_url` | string |
| `thumb_url` | ?string |
| `description_html` | string (HTML) |

### Media Collections & Conversions

Collection: `cover` (single file, stored on `r2_private`)
Conversions stored on: `r2_public`

| Conversion | Width | Format | Quality | Queued? |
|---|---|---|---|---|
| `thumb` | 320px | WebP | 80 | No |

### Relationships

- `chapters()` — `HasMany(Chapter)` ordered by `order`
- `tags()` — `BelongsToMany(Tag)`

### Query Scopes

- `scopePublished($query)`
- `scopeFeatured($query)`

---

## Model: `App\Models\Chapter`

File: `app/Models/Chapter.php`

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `book_id` | bigint FK | |
| `title` | string | |
| `slug` | string | Not auto-generated (set manually) |
| `summary` | text | |
| `body_markdown` | longtext | Full chapter content in Markdown |
| `order` | integer | Position within the book |
| `is_sample` | boolean | Whether the chapter is freely readable |

### Computed Attributes

| Attribute | Returns |
|---|---|
| `body_html` | Markdown-rendered HTML via `MarkdownRenderer` |

### Relationships

- `book()` — `BelongsTo(Book)`

---

## Markdown Rendering

Both `Book::description_html` and `Chapter::body_html` use `App\Services\MarkdownRenderer`, which wraps `league/commonmark` (GFM variant) with:
- `html_input: strip` — raw HTML in Markdown is stripped (XSS protection)
- `allow_unsafe_links: false` — javascript: / data: links are blocked

See [[../Backend/Services]].

---

## Public Behaviour

- **Books Index** (`/books`) — all published books, latest by `release_date`
- **Book Show** (`/books/{slug}`) — book detail page with chapters listed

## Admin Operations

Full CRUD for books and chapters. Chapters are managed independently (separate resource) and linked to a book via `book_id`.

## Related

- [[../Architecture/Database Schema]]
- [[../Backend/Services]]
- [[../Backend/Controllers - Public]]
- [[../Backend/Controllers - Admin]]
