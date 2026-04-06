# Admin Controllers

Tags: #backend #controllers #admin

All admin controllers live in `app/Http/Controllers/Admin/`. The entire `/admin` prefix group is protected by middleware: `auth`, `verified`, `role:admin`.

---

## `DashboardController`

File: `app/Http/Controllers/Admin/DashboardController.php`

### `index()` → `GET /admin`

Renders the admin dashboard (`Admin/Dashboard`).

---

## `ArtworkController`

File: `app/Http/Controllers/Admin/ArtworkController.php`

Full resource controller. Additional non-resource actions:

| Method | Route | Permission |
|---|---|---|
| `index()` | `GET /admin/artworks` | `admin` |
| `create()` | `GET /admin/artworks/create` | `admin` |
| `store(Request)` | `POST /admin/artworks` | `can upload art` |
| `edit(Artwork)` | `GET /admin/artworks/{id}/edit` | `admin` |
| `update(Request, Artwork)` | `PUT /admin/artworks/{id}` | `can edit art` |
| `destroy(Artwork)` | `DELETE /admin/artworks/{id}` | `admin` |
| `regenerate(Artwork)` | `POST /admin/artworks/{id}/regenerate` | `can regenerate image thumbnails` |
| `bulkRegenerate(Request)` | `POST /admin/artworks/bulk-regenerate` | `can regenerate image thumbnails` |

On image upload (store or update): clears old media, adds new media to `artwork` collection, dispatches `RegenerateArtworkImages` job.

---

## `GalleryController`

File: `app/Http/Controllers/Admin/GalleryController.php`

Standard resource controller. Passes all galleries for the gallery list and all artworks for assignment in create/edit forms.

---

## `BookController`

File: `app/Http/Controllers/Admin/BookController.php`

Standard resource controller for books.

---

## `ChapterController`

File: `app/Http/Controllers/Admin/ChapterController.php`

Standard resource controller for chapters. Chapters are linked to a book via `book_id` in the form.

---

## `TagController`

File: `app/Http/Controllers/Admin/TagController.php`

Standard resource controller. Manages `name`, `slug`, `type` fields.

---

## `FeaturedItemController`

File: `app/Http/Controllers/Admin/FeaturedItemController.php`

Standard resource controller. Manages polymorphic featured items (`item_type`, `item_id`, `display_context`, `display_order`).

---

## `MessageController`

File: `app/Http/Controllers/Admin/MessageController.php`

Read/update/delete only (no `create`/`store`). Routes: `index`, `show`, `update`, `destroy`.

---

## `UserController`

File: `app/Http/Controllers/Admin/UserController.php`

Full CRUD for user management.

---

## `OriginalMediaController`

File: `app/Http/Controllers/Admin/OriginalMediaController.php`

### `show(Media $media)` → `GET /admin/media/{media}/original`

Protected by middleware: `permission:can view source image`

Serves or redirects to the private original file on R2. The `view-original` gate is also defined in `AppServiceProvider`.

---

## Authorization Pattern

All admin controller actions call `$this->authorize(...)` with either:
- `'admin'` — catches the `Gate::before` rule granting admins everything
- A specific permission string — for granular operations like upload, edit, regenerate, view original

---

## Related

- [[../Domains/Artwork]]
- [[../Infrastructure/Permissions & Roles]]
- [[../Backend/Jobs]]
- [[../Routing/Routes]]
