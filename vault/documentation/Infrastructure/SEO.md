# SEO

Tags: #infrastructure #seo #opengraph #jsonld

## Overview

SEO metadata is built server-side and passed to the frontend as a shared Inertia prop (`seo`). Every public page calls `Inertia::share('seo', SeoBuilder::for*())` in its controller method.

---

## `SeoBuilder`

File: `app/Support/Seo/SeoBuilder.php`

Static factory class. Provides a named factory method for each page type:

| Method | Used by |
|---|---|
| `forHome()` | `HomeController::index` |
| `forArtIndex()` | `ArtworkController::index`, `GalleryController::index` |
| `forArtwork(Artwork)` | `ArtworkController::show` |
| `forGallery(Gallery)` | `GalleryController::show` |
| `forBooksIndex()` | `BookController::index` |
| `forBook(Book)` | `BookController::show` |
| `forContact()` | `ContactController::create` |

All delegate to the base `make()` method.

### `make()` Parameters

```php
public static function make(
    string $title,
    ?string $description = null,
    ?string $type = 'website',
    ?string $image = null,
    ?string $imageAlt = null,
    ?array $jsonld = null,
    string $robots = 'index,follow',
    bool $appendBrand = true
): SeoPayload
```

- **`appendBrand`** — appends ` - {app.name}` to the title unless false or title equals the app name
- **Canonical URL** — built from `url()->current()`, query strings stripped
- **Description** — normalised: HTML stripped, markdown syntax stripped, max 160 chars

### Fallback OG Image

When no specific image is available, `getFallbackOgImage()` queries:
1. Most recent featured published artwork
2. Most recent published artwork (any)
3. Returns `null` if no artworks exist

### JSON-LD

Generated for artworks (`VisualArtwork` schema) and books (`Book` schema). Includes `creator`/`author` as the app name.

---

## `SeoPayload`

File: `app/Support/Seo/SeoPayload.php`

Value object / DTO. Implements `Arrayable`.

### Properties

| Property | Type | Notes |
|---|---|---|
| `title` | string | Full title with brand suffix |
| `description` | string | Normalised, max 160 chars |
| `canonical` | string | Absolute canonical URL |
| `robots` | string | Default: `index,follow` |
| `og` | array | Open Graph meta tags |
| `twitter` | array | Twitter Card meta tags |
| `jsonld` | array/object/null | Structured data |
| `locale` | string | Default: `en_US` |
| `site_name` | string | From `config('app.name')` |

### `og` defaults (merged with constructor input)

```php
'type'        => 'website',
'title'       => $this->title,
'description' => $this->description,
'url'         => $this->canonical,
'site_name'   => $this->site_name,
```

### `twitter` defaults

```php
'card'        => 'summary_large_image',
'title'       => $this->title,
'description' => $this->description,
```

---

## Frontend Rendering

The `seo` shared prop is available to all Inertia pages. `PublicLayout.vue` reads and renders it as:
- `<title>` tag
- `<meta name="description">` / `<meta name="robots">`
- `<link rel="canonical">`
- `<meta property="og:*">` tags
- `<meta name="twitter:*">` tags
- `<script type="application/ld+json">` for JSON-LD

---

## Related

- [[../Backend/Controllers - Public]]
- [[../Frontend/Layouts]]
