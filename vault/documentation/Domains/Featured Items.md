# Featured Items Domain

Tags: #domain #featured

## Model: `App\Models\FeaturedItem`

File: `app/Models/FeaturedItem.php`

`FeaturedItem` is a polymorphic join table that allows arbitrary content to be pinned as "featured" in different display contexts (e.g. homepage spotlight, sidebar, etc.).

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `item_type` | string | Morph type: `artwork` or `book` |
| `item_id` | bigint | Morph ID |
| `display_context` | string | Context hint for the frontend (e.g. `home`) |
| `display_order` | integer | Ordering within a context |

### Morph Map

Defined in `AppServiceProvider::boot()`:

```php
Relation::morphMap([
    'artwork' => 'App\Models\Artwork',
    'book'    => 'App\Models\Book',
]);
```

This means `item_type` stores the short alias (`artwork` / `book`) rather than the full class name.

### Relationships

- `item()` — `MorphTo()` — resolves to `Artwork` or `Book`

## Usage

`FeaturedItem` records are managed through the admin panel. The home page queries artworks and books directly using `scopeFeatured()` on those models (the `featured_flag` column), not via this model. `FeaturedItem` provides a separate mechanism for context-aware curation.

## Admin Operations

Full CRUD.

## Related

- [[Artwork]]
- [[Book & Chapter]]
- [[../Architecture/Database Schema]]
- [[../Domains/User & Auth]]
