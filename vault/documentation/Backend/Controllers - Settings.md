# Settings Controllers

Tags: #backend #controllers #settings #auth

All settings controllers live in `app/Http/Controllers/Settings/` and are available to any authenticated + verified user (not admin-only).

---

## `ProfileController`

File: `app/Http/Controllers/Settings/ProfileController.php`

| Action | Route | Description |
|---|---|---|
| `edit()` | `GET /settings/profile` | Render profile edit page |
| `update(Request)` | `PATCH /settings/profile` | Update name and/or email |
| `destroy(Request)` | `DELETE /settings/profile` | Delete own account |

When email is changed, `email_verified_at` is cleared and a new verification email is triggered (Fortify).

---

## `PasswordController`

File: `app/Http/Controllers/Settings/PasswordController.php`

| Action | Route | Description |
|---|---|---|
| `edit()` | `GET /settings/password` | Render password change page |
| `update(Request)` | `PUT /settings/password` | Update password (throttled: 6/min) |

---

## `TwoFactorAuthenticationController`

File: `app/Http/Controllers/Settings/TwoFactorAuthenticationController.php`

| Action | Route | Description |
|---|---|---|
| `show()` | `GET /settings/two-factor` | Render 2FA management page |

Actual 2FA enable/disable/confirm/recovery-code actions are handled by Fortify's built-in routes.

---

## Related

- [[../Domains/User & Auth]]
- [[../Infrastructure/Auth - Fortify]]
- [[../Routing/Routes]]
