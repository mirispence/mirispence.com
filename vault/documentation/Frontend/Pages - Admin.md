# Admin Pages

Tags: #frontend #pages #admin #vue

All admin pages live in `resources/js/pages/Admin/`. They are only bundled in `admin.ts`, never in `app.ts`.

---

## `Admin/Dashboard.vue`

Route: `GET /admin`

Admin home page. Overview/statistics panel.

---

## Artworks

| Page | Route | Description |
|---|---|---|
| `Admin/Artworks/Index.vue` | `GET /admin/artworks` | Paginated list; per-row regenerate; bulk regenerate |
| `Admin/Artworks/Create.vue` | `GET /admin/artworks/create` | Upload image, set metadata, assign galleries/tags |
| `Admin/Artworks/Edit.vue` | `GET /admin/artworks/{id}/edit` | Edit metadata, replace image |

The Index page shows `image_status` badges and provides individual/bulk regeneration buttons.

---

## Galleries

| Page | Route | Description |
|---|---|---|
| `Admin/Galleries/Index.vue` | `GET /admin/galleries` | Gallery list |
| `Admin/Galleries/Create.vue` | `GET /admin/galleries/create` | Create gallery with cover image |
| `Admin/Galleries/Edit.vue` | `GET /admin/galleries/{id}/edit` | Edit gallery, manage artworks in gallery |

---

## Books

| Page | Route | Description |
|---|---|---|
| `Admin/Books/Index.vue` | `GET /admin/books` | Book list |
| `Admin/Books/Create.vue` | `GET /admin/books/create` | Create book with cover |
| `Admin/Books/Edit.vue` | `GET /admin/books/{id}/edit` | Edit book |

---

## Chapters

| Page | Route | Description |
|---|---|---|
| `Admin/Chapters/Index.vue` | `GET /admin/chapters` | Chapter list |
| `Admin/Chapters/Create.vue` | `GET /admin/chapters/create` | Create chapter with markdown editor |
| `Admin/Chapters/Edit.vue` | `GET /admin/chapters/{id}/edit` | Edit chapter |

---

## Tags

| Page | Route | Description |
|---|---|---|
| `Admin/Tags/Index.vue` | `GET /admin/tags` | Tag list |
| `Admin/Tags/Create.vue` | `GET /admin/tags/create` | Create tag with type |
| `Admin/Tags/Edit.vue` | `GET /admin/tags/{id}/edit` | Edit tag |

---

## Featured Items

| Page | Route | Description |
|---|---|---|
| `Admin/FeaturedItems/Index.vue` | `GET /admin/featured-items` | Featured items list |
| `Admin/FeaturedItems/Create.vue` | `GET /admin/featured-items/create` | Add featured item |
| `Admin/FeaturedItems/Edit.vue` | `GET /admin/featured-items/{id}/edit` | Edit featured item |

---

## Messages

| Page | Route | Description |
|---|---|---|
| `Admin/Messages/Index.vue` | `GET /admin/messages` | Inbox of contact messages |
| `Admin/Messages/Show.vue` | `GET /admin/messages/{id}` | View message detail, update status |

No create form — messages come from the public contact form.

---

## Users

| Page | Route | Description |
|---|---|---|
| `Admin/Users/Index.vue` | `GET /admin/users` | User list |
| `Admin/Users/Create.vue` | `GET /admin/users/create` | Create user |
| `Admin/Users/Edit.vue` | `GET /admin/users/{id}/edit` | Edit user, assign roles |

---

## Styling Notes

Admin pages currently use a mix of ad-hoc Tailwind and the admin form CSS system in `resources/css/admin/forms.css`. A full restyle (TASK-002) is pending: it will apply `.admin-page`, `.admin-card`, `.form-row`, `.control`, `.actions-bar` classes consistently.

---

## Related

- [[Layouts]]
- [[../Backend/Controllers - Admin]]
- [[../Infrastructure/Permissions & Roles]]
