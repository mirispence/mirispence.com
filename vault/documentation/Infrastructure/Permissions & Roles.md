# Permissions & Roles

Tags: #infrastructure #auth #permissions #roles #spatie

## Package

**Spatie Laravel Permission v6** (`config/permission.php`).

---

## Role: `admin`

The only role used in the application. Implicitly granted all permissions via `Gate::before` in `AppServiceProvider`:

```php
Gate::before(function ($user, $ability) {
    return $user->hasRole('admin') ? true : null;
});
```

This means any call to `$this->authorize(...)` or `Gate::allows(...)` returns `true` for an admin, regardless of the specific ability — without needing to assign every permission individually.

---

## Named Permissions

| Permission | Used in | Notes |
|---|---|---|
| `can upload art` | `ArtworkController::store` | Authorises creating a new artwork |
| `can edit art` | `ArtworkController::update` | Authorises updating an artwork |
| `can view source image` | `OriginalMediaController::show`, `view-original` Gate, route middleware | Authorises viewing the original private R2 file |
| `can regenerate image thumbnails` | `ArtworkController::regenerate`, `ArtworkController::bulkRegenerate`, route middleware | Authorises queuing image regeneration |
| `admin` | Various `$this->authorize('admin')` calls | General admin gate; caught by `Gate::before` |

---

## Custom Gates

### `view-original`

Defined in `AppServiceProvider::boot()`:

```php
Gate::define('view-original', function ($user) {
    return $user->hasPermissionTo('can view source image');
});
```

Used to protect access to private original media. A user must explicitly hold the `can view source image` permission (not just be an admin — though `Gate::before` means admins pass anyway).

---

## Route Middleware

The admin route group uses:

```php
->middleware(['auth', 'verified', 'role:admin'])
```

Individual routes use permission middleware:

```php
->middleware(['permission:can view source image'])
->middleware(['permission:can regenerate image thumbnails'])
```

---

## Spatie Morph Map Note

Roles and permissions are attached to the `User` model via `HasRoles`. The Spatie permission tables (`roles`, `permissions`, `model_has_roles`, etc.) are created by the `create_permission_tables` migration.

---

## Related

- [[../Domains/User & Auth]]
- [[../Infrastructure/Auth - Fortify]]
- [[../Backend/Controllers - Admin]]
