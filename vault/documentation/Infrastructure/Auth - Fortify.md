# Authentication — Laravel Fortify

Tags: #infrastructure #auth #fortify #2fa

## Overview

Authentication is handled by **Laravel Fortify** (headless). It provides the backend logic; all views are Inertia/Vue pages.

---

## Features Enabled

Configured in `config/fortify.php`. Standard Fortify features in use:

- `Features::registration()` — user registration
- `Features::resetPasswords()` — password reset via email
- `Features::emailVerification()` — email verification required
- `Features::updateProfileInformation()` — update name/email
- `Features::updatePasswords()` — change password
- `Features::twoFactorAuthentication()` — TOTP 2FA

---

## Fortify Actions (`app/Actions/Fortify/`)

| File | Purpose |
|---|---|
| `CreateNewUser.php` | Creates a user record on registration. Enforces password validation rules. |
| `ResetUserPassword.php` | Resets a user's password. Enforces password validation rules. |
| `PasswordValidationRules.php` | Shared trait providing the password rule set used by CreateNewUser and ResetUserPassword |

---

## Auth Views (Inertia Pages)

Pages in `resources/js/pages/auth/`:

- `Login.vue`
- `Register.vue`
- `ForgotPassword.vue`
- `ResetPassword.vue`
- `VerifyEmail.vue`
- `ConfirmPassword.vue`

All use `AuthLayout.vue`.

---

## Two-Factor Authentication

Setup is managed at `/settings/two-factor` (see `TwoFactorAuthenticationController`).

Components:
- `TwoFactorSetupModal.vue` — walk through enabling TOTP
- `TwoFactorRecoveryCodes.vue` — display and regenerate recovery codes

Fortify's built-in routes handle the enable/confirm/disable/regenerate-codes lifecycle.

---

## Middleware

| Middleware | Applied To |
|---|---|
| `auth` | Dashboard, settings, admin |
| `verified` | Dashboard, admin (email verification required) |
| `role:admin` | All `/admin` routes |

---

## `FortifyServiceProvider`

File: `app/Providers/FortifyServiceProvider.php`

Registers the Inertia response factories for each Fortify view (login, register, etc.) and binds the Fortify action implementations.

---

## Related

- [[../Domains/User & Auth]]
- [[../Infrastructure/Permissions & Roles]]
- [[../Routing/Routes]]
