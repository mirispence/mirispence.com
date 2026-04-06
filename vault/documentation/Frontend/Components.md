# Shared Components

Tags: #frontend #components #vue

All shared components live in `resources/js/components/`. UI primitives from Reka UI live in `resources/js/components/ui/`.

---

## Layout / Shell Components

| Component | Purpose |
|---|---|
| `AppShell.vue` | Outermost app container |
| `AppContent.vue` | Main content area wrapper |
| `AppHeader.vue` | Public site header / nav |
| `AppSidebar.vue` | Authenticated sidebar (with sections) |
| `AppSidebarHeader.vue` | Sidebar header section |
| `AppLogo.vue` | Full logo (text + icon) |
| `AppLogoIcon.vue` | Logo icon only |

---

## Navigation Components

| Component | Purpose |
|---|---|
| `NavMain.vue` | Main navigation items list |
| `NavUser.vue` | User menu in sidebar footer |
| `NavFooter.vue` | Sidebar footer links |
| `Breadcrumbs.vue` | Breadcrumb trail |

---

## Content Components

| Component | Purpose |
|---|---|
| `ArtCard.vue` | Artwork thumbnail card for grids |
| `SignedImage.vue` | Renders images from admin-protected URLs |
| `Pagination.vue` | Paginator — accepts `meta.links` array |
| `Heading.vue` | Page-level heading |
| `HeadingSmall.vue` | Section-level heading |
| `TextLink.vue` | Styled anchor/InertiaLink |
| `PlaceholderPattern.vue` | Placeholder for missing images |
| `Icon.vue` | SVG icon renderer |

---

## Form Components

| Component | Purpose |
|---|---|
| `FormSelect.vue` | Styled `<select>` element |
| `FormTextarea.vue` | Styled `<textarea>` element |
| `InputError.vue` | Validation error message display |
| `AlertError.vue` | Alert-level error banner |

---

## Auth / User Components

| Component | Purpose |
|---|---|
| `DeleteUser.vue` | Account deletion confirmation modal |
| `UserInfo.vue` | User name + email display |
| `UserMenuContent.vue` | Dropdown menu content for user avatar |
| `TwoFactorRecoveryCodes.vue` | Recovery codes display at 2FA setup |
| `TwoFactorSetupModal.vue` | Modal for enabling 2FA |

---

## `Pagination.vue`

Accepts an array of link objects in the shape Inertia/Laravel resources provide:

```typescript
interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}
```

Usage in pages: pass `artworks.meta.links` (not `artworks.links`).

---

## UI Primitives (`components/ui/`)

Reka UI component wrappers (buttons, dialogs, dropdowns, inputs, labels, toasts, etc.). These are low-level and used by the components above.

---

## Related

- [[Layouts]]
- [[Pages - Public]]
- [[Pages - Admin]]
