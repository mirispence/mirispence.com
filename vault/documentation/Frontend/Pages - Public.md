# Public Pages

Tags: #frontend #pages #public #vue

All public pages live in `resources/js/pages/Public/`. They use `PublicLayout`.

---

## `Public/Home.vue`

Route: `GET /`

Props:
- `featuredArtworks` — up to 3 featured published artworks (via `PublicArtworkResource`)
- `featuredBooks` — up to 3 featured published books

Displays the portfolio homepage with hero/featured artwork and featured books sections.

---

## `Public/Art/Index.vue`

Route: `GET /art`

Props:
- `artworks` — paginated `PublicArtworkResource` collection (6/page) with `data`, `links`, `meta`
- `filters` — `{ tag?, gallery? }` — active filter values
- `galleries` — all published galleries (for filter UI)

Features:
- Artwork grid with responsive images
- Filter by tag or gallery via query string
- Pagination via `meta.links` (Inertia `Pagination` component)

---

## `Public/Art/Show.vue`

Route: `GET /art/{slug}`

Props:
- `artwork` — single resolved `PublicArtworkResource` object

Displays a single artwork with full display image, description (HTML), tags, and gallery memberships.

---

## `Public/Galleries/Index.vue`

Route: `GET /galleries`

Props:
- `galleries` — paginated galleries (6/page) with `data`, `links`, `meta`

Grid of gallery thumbnails with pagination.

---

## `Public/Galleries/Show.vue`

Route: `GET /galleries/{slug}`

Props:
- `gallery` — single gallery object
- `artworks` — paginated `PublicArtworkResource` collection (6/page) within the gallery

Shows gallery metadata and paginated artwork grid. Artworks ordered by `pivot_sort_order`.

---

## `Public/Books/Index.vue`

Route: `GET /books`

Props:
- `books` — all published books

Book listing page.

---

## `Public/Books/Show.vue`

Route: `GET /books/{slug}`

Props:
- `book` — single book with chapters and tags loaded

Book detail page with cover image, description, external links, and chapter list.

---

## `Public/Contact.vue`

Route: `GET /contact`

Contact form page. Submits to `POST /contact`. Fields: name, email, subject, message, type (general/commission), budget_range, desired_due_date. Includes a hidden `honeypot` field.

---

## Pagination Shape

Controllers use `PublicArtworkResource::collection()` which wraps Laravel's paginator in:
```json
{
  "data": [...],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": {
    "current_page": 1,
    "links": [{ "url": null, "label": "«", "active": false }, ...]
  }
}
```

Vue components use `artworks.meta.links` (not `artworks.links`) for per-page link rendering.

---

## Related

- [[Layouts]]
- [[Components]]
- [[../Backend/Controllers - Public]]
- [[../Infrastructure/SEO]]
