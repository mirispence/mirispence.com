# Frontend Entry Points

Tags: #frontend #vite #inertia

## Overview

There are three JavaScript entry points. Two are compiled by Vite; one is the SSR entry.

| File | Purpose |
|---|---|
| `resources/js/app.ts` | Public site + auth pages |
| `resources/js/admin.ts` | Admin panel pages |
| `resources/js/ssr.ts` | Server-side rendering entry |

---

## `app.ts` — Public Entry

```typescript
createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob(['./pages/**/*.vue', '!./pages/Admin/**/*.vue']),
        ),
});
```

**Explicitly excludes** `./pages/Admin/**/*.vue` from the glob. Admin page components are never bundled into the public JavaScript bundle — they are only in `admin.ts`.

Imports `../css/app.css`.

---

## `admin.ts` — Admin Entry

```typescript
createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob('./pages/**/*.vue'),
        ),
});
```

Includes **all** pages (including admin). Used only when an admin page is rendered by Laravel.

Imports `../css/app.css`.

---

## `ssr.ts` — SSR Entry

Used for server-side rendering via Node. Follows the same pattern. SSR is configured in `vite.config.ts`:

```typescript
laravel({
    input: ['resources/js/app.ts', 'resources/js/admin.ts'],
    ssr: 'resources/js/ssr.ts',
})
```

---

## Vite Configuration (`vite.config.ts`)

Key plugins:

| Plugin | Role |
|---|---|
| `laravel-vite-plugin` | Manifest, hot reload, Inertia helpers |
| `@tailwindcss/vite` | Tailwind CSS v4 integration |
| `@vitejs/plugin-vue` | Vue SFC compilation |
| `@laravel/vite-plugin-wayfinder` | Generates typed TS route action files |
| `vite-plugin-static-copy` | Copies `resources/svg/*.svg` → `public/icons/` |

Wayfinder is configured with `formVariants: true` to generate form-aware typed action helpers.

---

## CSS

- `resources/css/app.css` — Tailwind CSS base + public/auth styles
- `resources/css/artwork.css` — Artwork-specific display styles
- `resources/css/admin/forms.css` — Admin form design system (pending TASK-002 application)

---

## Related

- [[../Architecture/Stack]]
- [[Layouts]]
- [[Pages - Public]]
- [[Pages - Admin]]
