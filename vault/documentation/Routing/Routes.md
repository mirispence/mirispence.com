# Routes

Tags: #routing #routes

## Public Routes (`routes/web.php`)

| Method | URI | Name | Controller |
|---|---|---|---|
| GET | `/` | `home` | `HomeController@index` |
| GET | `/art` | `art.index` | `ArtworkController@index` |
| GET | `/art/{artwork:slug}` | `art.show` | `ArtworkController@show` |
| GET | `/galleries` | `galleries.index` | `GalleryController@index` |
| GET | `/galleries/{gallery:slug}` | `galleries.show` | `GalleryController@show` |
| GET | `/books` | `books.index` | `BookController@index` |
| GET | `/books/{book:slug}` | `books.show` | `BookController@show` |
| GET | `/contact` | `contact.create` | `ContactController@create` |
| POST | `/contact` | `contact.store` | `ContactController@store` |
| GET | `/dashboard` | `dashboard` | _(inline Inertia render)_ |

The `dashboard` route requires `auth` + `verified` middleware.

Route model bindings use the `slug` column (e.g. `{artwork:slug}`).

---

## Settings Routes (`routes/settings.php`)

All require `auth` middleware.

| Method | URI | Name | Controller |
|---|---|---|---|
| GET | `/settings/profile` | `profile.edit` | `ProfileController@edit` |
| PATCH | `/settings/profile` | `profile.update` | `ProfileController@update` |
| DELETE | `/settings/profile` | `profile.destroy` | `ProfileController@destroy` |
| GET | `/settings/password` | `user-password.edit` | `PasswordController@edit` |
| PUT | `/settings/password` | `user-password.update` | `PasswordController@update` |
| GET | `/settings/two-factor` | `two-factor.show` | `TwoFactorAuthenticationController@show` |

`/settings` redirects to `/settings/profile`.

---

## Admin Routes (`routes/web.php` — `/admin` group)

Middleware: `auth`, `verified`, `role:admin`. Prefix: `admin`. Name prefix: `admin.`.

### Standard Resources

| Resource | URI prefix | Routes | Name prefix |
|---|---|---|---|
| Artworks | `/admin/artworks` | full CRUD | `admin.artworks.*` |
| Galleries | `/admin/galleries` | full CRUD | `admin.galleries.*` |
| Books | `/admin/books` | full CRUD | `admin.books.*` |
| Chapters | `/admin/chapters` | full CRUD | `admin.chapters.*` |
| Tags | `/admin/tags` | full CRUD | `admin.tags.*` |
| Featured Items | `/admin/featured-items` | full CRUD | `admin.featured-items.*` |
| Messages | `/admin/messages` | index, show, update, destroy | `admin.messages.*` |
| Users | `/admin/users` | full CRUD | `admin.users.*` |

### Custom Routes

| Method | URI | Middleware | Name | Description |
|---|---|---|---|---|
| GET | `/admin/media/{media}/original` | `permission:can view source image` | `admin.media.original` | Serve private original file |
| POST | `/admin/artworks/{artwork}/regenerate` | `permission:can regenerate image thumbnails` | `admin.artworks.regenerate` | Queue single image regeneration |
| POST | `/admin/artworks/bulk-regenerate` | `permission:can regenerate image thumbnails` | `admin.artworks.bulk-regenerate` | Bulk queue image regeneration |

---

## Auth Routes

Provided automatically by Fortify. Key routes include:
- `GET/POST /login`
- `POST /logout`
- `GET/POST /register`
- `GET/POST /forgot-password`
- `GET/POST /reset-password/{token}`
- `GET/POST /email/verify`
- `GET/POST /user/two-factor-*`

---

## TypeScript Route Helpers (Wayfinder)

Wayfinder generates type-safe action files in `resources/js/actions/` at build time. These can be used in Vue components instead of string route names:

```typescript
import { artIndex } from '@/actions/Art';
// or
import { adminArtworksStore } from '@/actions/Admin/Artworks';
```

---

## Related

- [[../Backend/Controllers - Public]]
- [[../Backend/Controllers - Admin]]
- [[../Backend/Controllers - Settings]]
- [[../Infrastructure/Auth - Fortify]]
- [[../Infrastructure/Permissions & Roles]]
