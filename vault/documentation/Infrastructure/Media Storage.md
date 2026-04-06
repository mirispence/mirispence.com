# Media Storage

Tags: #infrastructure #media #r2 #cloudflare #spatie

## Overview

All media (artwork images, gallery covers, book covers) is managed by **Spatie Media Library v11** and stored on **Cloudflare R2** using two separate buckets.

| Bucket | Disk name | Contents | Access |
|---|---|---|---|
| R2 Private | `r2_private` | Original uploaded files | Private (admin only, via signed URL or proxy) |
| R2 Public | `r2_public` | WebP conversion outputs | Public CDN |

---

## Filesystem Disks (`config/filesystems.php`)

### `r2_private`

```php
'driver'                  => 's3',
'key'                     => env('R2_ACCESS_KEY_ID'),
'secret'                  => env('R2_SECRET_ACCESS_KEY'),
'region'                  => 'us-east-1',
'bucket'                  => env('R2_PRIVATE_BUCKET'),
'url'                     => env('R2_PRIVATE_URL'),
'endpoint'                => env('R2_ENDPOINT'),
'use_path_style_endpoint' => true,
'throw'                   => true,
```

### `r2_public`

Same credentials, different bucket (`R2_PUBLIC_BUCKET` / `R2_PUBLIC_URL`).

### Required `.env` Variables

```
R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_ENDPOINT=
R2_PRIVATE_BUCKET=
R2_PUBLIC_BUCKET=
R2_PRIVATE_URL=
R2_PUBLIC_URL=
```

> **Known gap:** These variables are missing from `.env.example`.

---

## Spatie Media Library Configuration (`config/media-library.php`)

| Setting | Value |
|---|---|
| Default disk | `r2_private` |
| Conversions disk | `r2_public` |
| Max file size | 10 MB |
| Path generator | `App\Support\MediaLibrary\CustomPathGenerator` |
| Image driver | `gd` (env `IMAGE_DRIVER`) |
| Cache-Control header | `max-age=604800` (1 week) |

---

## `CustomPathGenerator`

File: `app/Support/MediaLibrary/CustomPathGenerator.php`

Generates storage paths using the media UUID:

| Path type | Pattern |
|---|---|
| Original | `img/{uuid}/` |
| Conversions | `img/{uuid}/conversions/` |
| Responsive images | `img/{uuid}/responsive-images/` |

Using UUID-based paths ensures paths remain stable even when media is updated.

`moves_media_on_update: true` is enabled in media-library config, so updating a media item moves it to the new UUID-based path automatically.

---

## Conversion Pipeline

When media is added to a collection, Spatie queues conversion jobs. The queued conversions for `Artwork` are:

| Name | Width | Format |
|---|---|---|
| `thumb` | 320px | WebP (non-queued — immediate) |
| `grid_640` | 640px | WebP |
| `grid_960` | 960px | WebP |
| `display_1280` | 1280px | WebP |
| `display_1600` | 1600px | WebP |
| `display_2048` | 2048px | WebP |

Manual re-conversion is triggered via `RegenerateArtworkImages` job (calls `FileManipulator::createDerivedFiles`).

---

## Accessing Original Files (Admin)

Original files on `r2_private` are protected. Access is gated by the `view-original` Gate:

```php
Gate::define('view-original', function ($user) {
    return $user->hasPermissionTo('can view source image');
});
```

The route `/admin/media/{media}/original` is additionally protected by middleware `permission:can view source image`. See `OriginalMediaController`.

---

## Public URL Shape

Public conversions served from `r2_public` CDN. URL constructed by Spatie based on `R2_PUBLIC_URL` + `img/{uuid}/conversions/{conversion-name}.webp`.

The `PublicArtworkResource` only exposes conversion URLs — never the original:

```php
'media_urls' => [
    'thumb'   => $media->getUrl('thumb'),
    'grid'    => ['src' => ..., 'srcset' => '... 640w, ... 960w'],
    'display' => ['src' => ..., 'srcset' => '... 1280w, ... 1600w, ... 2048w'],
]
```

---

## Related

- [[../Domains/Artwork]]
- [[../Backend/Jobs]]
- [[../Infrastructure/Permissions & Roles]]
- [[../Architecture/Stack]]
