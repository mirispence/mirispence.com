# User & Auth Domain

Tags: #domain #user #auth #fortify #2fa

## Model: `App\Models\User`

File: `app/Models/User.php`

Uses traits: `HasFactory`, `HasRoles` (Spatie), `Notifiable`, `TwoFactorAuthenticatable` (Fortify).

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `name` | string | |
| `email` | string | |
| `password` | string | Auto-hashed via cast |
| `role` | string | Legacy field; Spatie Permission manages roles separately |

### Hidden Fields (never serialised)

- `password`
- `two_factor_secret`
- `two_factor_recovery_codes`
- `remember_token`

### Casts

- `email_verified_at` → `datetime`
- `password` → `hashed`
- `two_factor_confirmed_at` → `datetime`

---

## Authentication

Handled by **Laravel Fortify** (headless). Features enabled (see `config/fortify.php`):
- Login / Logout
- Registration
- Password reset
- Email verification
- Two-factor authentication (TOTP)

Fortify actions (in `app/Actions/Fortify/`):
- `CreateNewUser` — creates a user on registration
- `ResetUserPassword` — resets password
- `PasswordValidationRules` — shared password rules trait

Inertia views for auth pages live in `resources/js/pages/auth/`.

---

## Two-Factor Authentication

Managed via `TwoFactorAuthenticationController` at `/settings/two-factor`. Uses Fortify's built-in TOTP. Recovery codes are shown to the user at setup via `TwoFactorRecoveryCodes.vue` component.

---

## Roles & Permissions

Handled by **Spatie Laravel Permission**. See [[../Infrastructure/Permissions & Roles]] for full details.

**Key principle:** `Gate::before` in `AppServiceProvider` grants the `admin` role all gate checks implicitly:

```php
Gate::before(function ($user, $ability) {
    return $user->hasRole('admin') ? true : null;
});
```

### Named Permissions (used in code)

| Permission | Used by |
|---|---|
| `can upload art` | `ArtworkController::store()` |
| `can edit art` | `ArtworkController::update()` |
| `can view source image` | `OriginalMediaController`, `view-original` Gate |
| `can regenerate image thumbnails` | `ArtworkController::regenerate/bulkRegenerate` |
| `admin` | General admin gate (`$this->authorize('admin')`) |

---

## Settings Routes

Under `/settings/*` (auth middleware, no admin requirement):

| Route | Controller | Description |
|---|---|---|
| `GET /settings/profile` | `ProfileController::edit` | Profile edit page |
| `PATCH /settings/profile` | `ProfileController::update` | Update name/email |
| `DELETE /settings/profile` | `ProfileController::destroy` | Delete account |
| `GET /settings/password` | `PasswordController::edit` | Password change page |
| `PUT /settings/password` | `PasswordController::update` | Update password (throttled 6/min) |
| `GET /settings/two-factor` | `TwoFactorAuthenticationController::show` | 2FA management page |

---

## Admin User Management

Full CRUD under `/admin/users`. Allows admin to create, update, and delete user accounts.

## Related

- [[../Infrastructure/Auth - Fortify]]
- [[../Infrastructure/Permissions & Roles]]
- [[../Routing/Routes]]
