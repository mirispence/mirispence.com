# Layouts

Tags: #frontend #layouts #vue

Layout files live in `resources/js/Layouts/`.

---

## `AppLayout.vue`

The shell for the authenticated user area (dashboard, settings). Includes the sidebar navigation (`AppSidebar`, `NavMain`, `NavUser`, `NavFooter`) and uses `AppShell`.

---

## `AdminLayout.vue`

Used by all admin panel pages. Wraps admin pages with the admin navigation chrome. The Books nav item is hidden when the `books` flag is off.

---

## `AuthLayout.vue`

Minimal centered layout for auth pages (login, register, password reset, email verification). No navigation.

---

## `PublicLayout.vue`

Public site wrapper. Includes `AppHeader` for site navigation. Used by all public-facing pages. The Books nav link (desktop and mobile Sheet) shows only when `useFeatures().books` is true.

---

## SEO Rendering

All pages that use `PublicLayout` benefit from the SEO data shared via `Inertia::share('seo', ...)` in controllers. The layout is responsible for rendering `<title>`, `<meta>`, OpenGraph, Twitter Card, and JSON-LD tags from the `seo` shared prop.

---

## Related

- [[Entry Points]]
- [[Pages - Public]]
- [[Pages - Admin]]
- [[Components]]
