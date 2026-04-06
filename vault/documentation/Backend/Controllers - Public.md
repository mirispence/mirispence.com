# Public Controllers

Tags: #backend #controllers #public

All public controllers live in `app/Http/Controllers/Public/` and extend `App\Http\Controllers\Controller`.

Every controller method calls `Inertia::share('seo', SeoBuilder::for*())` before rendering to push SEO metadata into the shared Inertia props.

---

## `HomeController`

File: `app/Http/Controllers/Public/HomeController.php`

### `index()` → `GET /`

- Queries up to 3 featured + published artworks (ordered by `created_on` desc)
- Queries up to 3 featured + published books (ordered by `release_date` desc)
- Renders `Public/Home` with `featuredArtworks` and `featuredBooks` props
- Artworks passed through `PublicArtworkResource` (strips original URLs)

---

## `ArtworkController`

File: `app/Http/Controllers/Public/ArtworkController.php`

### `index(Request $request)` → `GET /art`

- Queries published artworks, ordered by `created_on` desc, paginated **6/page**
- Optional filters via query string:
  - `?tag={slug}` — filter by tag slug
  - `?gallery={slug}` — filter by gallery slug
- `withQueryString()` preserves filters in pagination links
- Renders `Public/Art/Index` with `artworks` (ResourceCollection), `filters`, and `galleries` props

### `show(Artwork $artwork)` → `GET /art/{slug}`

- Uses route model binding by `slug`
- 404s if `publish_status !== 'published'`
- Unsets media relation then re-loads `tags` and `galleries`
- Renders `Public/Art/Show` with single `artwork` prop via `PublicArtworkResource`

---

## `GalleryController`

File: `app/Http/Controllers/Public/GalleryController.php`

### `index()` → `GET /galleries`

- Queries published galleries, ordered by `sort_order`, paginated 6/page
- Renders `Public/Galleries/Index`

### `show(Gallery $gallery)` → `GET /galleries/{slug}`

- 404s if not published
- Queries artwork pivot separately: published artworks in gallery, ordered by `pivot_sort_order`, paginated **6/page** with query string
- Renders `Public/Galleries/Show` with `gallery` and `artworks` props

---

## `BookController`

File: `app/Http/Controllers/Public/BookController.php`

### `index()` → `GET /books`

- Published books, ordered by `release_date` desc
- Renders `Public/Books/Index`

### `show(Book $book)` → `GET /books/{slug}`

- 404s if not published
- Loads chapters and tags
- Renders `Public/Books/Show`

---

## `ContactController`

File: `app/Http/Controllers/Public/ContactController.php`

### `create()` → `GET /contact`

Renders `Public/Contact`.

### `store(ContactRequest $request)` → `POST /contact`

1. Rate limit check (5 attempts per IP per hour)
2. Validated data + `metadata` (IP, user agent)
3. Creates `ContactMessage`
4. If `type = commission`, creates linked `CommissionRequest`
5. Redirects back with `success` flash

See [[../Domains/Contact & Commission]] for full validation rules.

---

## Related

- [[../Infrastructure/SEO]]
- [[../Domains/Artwork]]
- [[../Domains/Gallery]]
- [[../Domains/Book & Chapter]]
- [[../Domains/Contact & Commission]]
- [[../Routing/Routes]]
