# Database Schema

Tags: #architecture #database #schema

## Tables

### `users`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | |
| `email` | string unique | |
| `email_verified_at` | timestamp nullable | |
| `password` | string (hashed) | |
| `two_factor_secret` | text nullable | Fortify 2FA |
| `two_factor_recovery_codes` | text nullable | Fortify 2FA |
| `two_factor_confirmed_at` | timestamp nullable | Fortify 2FA |
| `remember_token` | string nullable | |
| `created_at` / `updated_at` | timestamps | |

### `artworks`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `title` | string | |
| `slug` | string unique | Auto-generated via `HasUniqueSlug` |
| `description` | text nullable | Markdown |
| `alt_text` | string nullable | |
| `created_on` | date nullable | Artwork creation date (not DB timestamp) |
| `publish_status` | string | `draft` or `published` |
| `nsfw_flag` | boolean | |
| `featured_flag` | boolean | |
| `metadata` | json nullable | |
| `image_status` | string nullable | `pending`, `processing`, `ready`, `failed` |
| `image_error` | text nullable | Error message if `image_status = failed` |
| `image_processed_at` | timestamp nullable | |
| `created_at` / `updated_at` | timestamps | |

### `galleries`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | |
| `slug` | string unique | Auto-generated |
| `description` | text nullable | Markdown |
| `sort_order` | integer | Gallery list ordering |
| `publish_status` | string | `draft` or `published` |
| `created_at` / `updated_at` | timestamps | |

### `books`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `title` | string | |
| `slug` | string unique | Auto-generated |
| `description` | text nullable | Markdown |
| `publish_status` | string | `draft` or `published` |
| `featured_flag` | boolean | |
| `release_date` | date nullable | |
| `external_links` | json nullable | Array of external link objects |
| `created_at` / `updated_at` | timestamps | |

### `chapters`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `book_id` | bigint FK → books | |
| `title` | string | |
| `slug` | string | |
| `summary` | text nullable | |
| `body_markdown` | longtext nullable | Raw markdown content |
| `order` | integer | Position within the book |
| `is_sample` | boolean | Whether the chapter is publicly readable |
| `created_at` / `updated_at` | timestamps | |

### `tags`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | |
| `slug` | string | |
| `type` | string | `artwork`, `book`, or `both` |
| `created_at` / `updated_at` | timestamps | |

### `contact_messages`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | |
| `email` | string | |
| `subject` | string | |
| `message` | text | |
| `type` | string | `general` or `commission` |
| `status` | string | Admin-managed status |
| `metadata` | json nullable | IP, user agent |
| `created_at` / `updated_at` | timestamps | |

### `commission_requests`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `contact_message_id` | bigint FK → contact_messages | |
| `project_description` | text | |
| `budget_range` | string nullable | |
| `desired_due_date` | date nullable | |
| `status` | string | `new`, etc. |
| `created_at` / `updated_at` | timestamps | |

> **Note:** `CommissionRequest` model/migration exists but has **no controller or admin UI** (TASK-003 in backlog).

### `featured_items`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `item_type` | string | Morph type (`artwork` or `book`) |
| `item_id` | bigint | Morph ID |
| `display_context` | string | Context hint |
| `display_order` | integer | |
| `created_at` / `updated_at` | timestamps | |

### `media` (Spatie Media Library)
Managed entirely by Spatie Media Library. Key columns: `id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`.

### Spatie Permission tables
`permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions`

## Pivot Tables

| Pivot Table | Relationship |
|---|---|
| `artwork_gallery` | `artworks` ↔ `galleries` (with `sort_order`) |
| `artwork_tag` | `artworks` ↔ `tags` |
| `book_tag` | `books` ↔ `tags` |

## Relationships Diagram

```
User ──── (Spatie Roles) ──── Role ──── Permission

Artwork ──┬── BelongsToMany ──── Gallery
          └── BelongsToMany ──── Tag
          └── HasMany (media) ── Media (Spatie)

Gallery ─── HasMany (media) ─── Media (Spatie)

Book ──┬── HasMany ──── Chapter
       └── BelongsToMany ──── Tag
       └── HasMany (media) ─── Media (Spatie)

ContactMessage ── HasOne ── CommissionRequest

FeaturedItem ── MorphTo ── Artwork | Book
```

## Related

- [[../Domains/Artwork]]
- [[../Domains/Gallery]]
- [[../Domains/Book & Chapter]]
- [[../Domains/Contact & Commission]]
- [[../Infrastructure/Media Storage]]
