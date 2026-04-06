# Tag Domain

Tags: #domain #tag

## Model: `App\Models\Tag`

File: `app/Models/Tag.php`

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `name` | string | Display name |
| `slug` | string | URL-safe identifier |
| `type` | string | `artwork` \| `book` \| `both` |

> Tags do **not** use the `HasUniqueSlug` trait — the slug must be set explicitly.

### Relationships

- `artworks()` — `BelongsToMany(Artwork)`
- `books()` — `BelongsToMany(Book)`

### `type` Field Logic

The `type` field restricts which content types a tag can be assigned to. In the admin artwork form, only tags with `type = 'artwork'` or `type = 'both'` are offered:

```php
Tag::whereIn('type', ['artwork', 'both'])->get()
```

## Public Filtering

On the art index page, visitors can filter artworks by tag slug via query string:
```
/art?tag=fantasy
```

## Admin Operations

Full CRUD. No special operations.

## Related

- [[Artwork]]
- [[Book & Chapter]]
- [[../Architecture/Database Schema]]
