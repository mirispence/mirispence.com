# Traits

Tags: #backend #traits

## `HasUniqueSlug`

File: `app/Traits/HasUniqueSlug.php`

Shared by: `Artwork`, `Gallery`, `Book`

### Purpose

Automatically generates a unique URL slug from a designated source field on model create and (when the source field changes) on model update.

### Contract

Models using this trait must implement:

```php
public function getSlugSourceField(): string;
```

Returns the name of the field to slugify (e.g. `'title'`, `'name'`).

### Boot Events

Registered via `bootHasUniqueSlug()` (Laravel's convention for trait boots):

- **`creating`** — always generates and assigns a slug
- **`updating`** — only regenerates the slug if the source field is dirty

### Uniqueness Algorithm

```php
public function generateUniqueSlug(string $value): string
{
    $slug = Str::slug($value);
    $originalSlug = $slug;
    $count = 1;

    while (static::where('slug', $slug)
        ->where($this->getKeyName(), '!=', $this->getKey())
        ->exists()) {
        $slug = "{$originalSlug}-{$count}";
        $count++;
    }

    return $slug;
}
```

Generates `my-title`, then `my-title-1`, `my-title-2`, etc. until a unique slug is found. Excludes the current model from the uniqueness check (important for updates).

### Helper

```php
public function getSlugSourceValue(): string
{
    return $this->getAttribute($this->getSlugSourceField()) ?? '';
}
```

---

## Related

- [[../Domains/Artwork]]
- [[../Domains/Gallery]]
- [[../Domains/Book & Chapter]]
