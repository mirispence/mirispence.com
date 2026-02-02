# Admin Form Restyle - Agentic AI Prompt

You are an agentic coding assistant working on a Laravel + Inertia + Vue 3 + TailwindCSS project.

Your task is to implement a shared admin form design system and apply it across the entire admin area, specifically:

- All Vue pages under `resources/js/Pages/Admin/**` that contain form fields
- This includes create, edit, settings, and any other form-like pages in that directory tree

This work must result in a consistent, modern, comfortable form layout everywhere in Admin.

## Objectives

1. Shared admin form styling that is applied everywhere
   - A single design system that can be reused across all admin pages
   - Minimal per-field Tailwind clutter in templates

2. Better layout and readability
   - Centered content width (no huge empty whitespace)
   - Card containers for forms
   - Clear spacing between rows
   - Consistent label and control alignment

3. Consistent inputs and errors
   - All controls (input/select/textarea/file/multi-select/checkbox) have consistent styling
   - Help and error text are consistently styled and positioned under the relevant field

4. Improve actions UX
   - Primary action (Save/Create/Update) is visually consistent across pages
   - Prefer a sticky bottom actions bar in admin forms so actions are always accessible

5. Do not change behavior
   - Do not change endpoints, payload shapes, field names, or validation behavior
   - Do not remove fields
   - Only restyle and restructure markup

## Key requirement

Yes - the prompt must include the new styles, and you must implement them as shared classes in the Tailwind component layer so they automatically work across all admin pages without repeating long Tailwind class lists.

## Constraints and guardrails

- Use TailwindCSS. Do not add UI libraries.
- Reduce template noise by extracting shared classes via `@layer components`.
- You may add ONE small wrapper component (optional) only if it reduces duplication without changing behavior.
- Keep text content as-is.
- Do not use em dashes in any added text.

## Implementation requirements

### 1) Add shared admin form classes

Create a new CSS file:

- `resources/css/admin/forms.css`

Define these classes using Tailwind `@layer components`:

```css
@layer components {
  .admin-page {
    @apply mx-auto max-w-5xl px-4 sm:px-6 lg:px-8;
  }

  .admin-header {
    @apply flex items-center justify-between gap-4;
  }

  .admin-title {
    @apply text-2xl font-semibold text-gray-900 sm:text-3xl;
  }

  .admin-subtitle {
    @apply mt-1 text-sm text-gray-600;
  }

  .admin-card {
    @apply rounded-xl bg-white shadow-sm ring-1 ring-gray-200;
  }

  .admin-card-body {
    @apply p-6 sm:p-8;
  }

  .form-stack {
    @apply space-y-6;
  }

  .form-section {
    @apply space-y-4;
  }

  .section-title {
    @apply text-base font-semibold text-gray-900;
  }

  .section-divider {
    @apply border-t border-gray-200;
  }

  .form-row {
    @apply grid grid-cols-1 gap-2 sm:grid-cols-12 sm:gap-6;
  }

  .form-label {
    @apply text-sm font-medium text-gray-800 sm:col-span-3 sm:pt-2;
  }

  .form-field {
    @apply sm:col-span-9;
  }

  .control {
    @apply block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm
           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20;
  }

  .control-textarea {
    @apply min-h-[110px];
  }

  .control-select {
    @apply pr-10;
  }

  .control-multiselect {
    @apply min-h-[120px];
  }

  .control-file {
    @apply block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900;
  }

  .help {
    @apply mt-1 text-xs text-gray-500;
  }

  .error {
    @apply mt-1 text-sm text-red-600;
  }

  .badge {
    @apply inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium;
  }

  .badge-green {
    @apply bg-green-100 text-green-800;
  }

  .badge-gray {
    @apply bg-gray-100 text-gray-800;
  }

  .btn-primary {
    @apply inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white
           shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
           disabled:cursor-not-allowed disabled:opacity-60;
  }

  .btn-secondary {
    @apply inline-flex items-center justify-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-900
           shadow-sm ring-1 ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
           disabled:cursor-not-allowed disabled:opacity-60;
  }

  .btn-link {
    @apply text-sm font-semibold text-indigo-600 hover:text-indigo-500;
  }

  .actions-bar {
    @apply sticky bottom-0 mt-8 border-t border-gray-200 bg-white/80 backdrop-blur;
  }

  .actions-bar-inner {
    @apply mx-auto flex max-w-5xl items-center justify-end gap-3 px-4 py-4 sm:px-6 lg:px-8;
  }
}
2) Ensure the stylesheet is compiled and loaded

Import `resources/css/admin/forms.css` into the main Tailwind entry that is already built by Vite (or into an admin-specific entry if one exists). If the project uses a single app stylesheet, import it there.

3) Apply styles across all admin pages with forms

Scan all files under:

- `resources/js/Pages/Admin/`

For each page containing a form or form fields:

- Wrap page content with `.admin-page`
- Add `.admin-header` and `.admin-title` at top
- Place the form inside `.admin-card` and `.admin-card-body`
- Convert each input group into the shared `.form-row` structure:

    - label uses `.form-label`
    - input area uses `.form-field`
- Apply `.control` classes to all form controls:

    - inputs: `.control`
    - textareas: `.control control-textarea`
    - selects: `.control control-select`
    - multi-selects: `.control control-multiselect`
    - file inputs: `.control-file` (or `.control` if file styling is acceptable, but prefer `.control-file`)
- Ensure errors render under the correct control using `.error`
- Ensure help text uses `.help`

If a page already uses a layout component (Admin layout), keep it, but apply these classes inside the page content.

4) Standardize action buttons

For all admin forms:

- Add a sticky actions bar at the bottom of the form using:

    - `.actions-bar` and `.actions-bar-inner`
- Primary button uses `.btn-primary`
- Secondary buttons use `.btn-secondary`
- Link-style actions use `.btn-link`

Preserve existing click handlers, routes, and processing/disabled states.

5) Artwork image section styling (special case)

On the Artwork Create/Edit pages, style the image area so it looks deliberate:

- Place "Current Image" and pipeline status into a bordered panel inside the card:

    - Use a simple internal panel: `rounded-lg ring-1 ring-gray-200 p-4`
- Pipeline badge uses:

    - `.badge` plus `.badge-green` for Ready, `.badge-gray` for other states
- "Regenerate" is a `.btn-link`
- If an image preview exists, apply:

    - `rounded-lg ring-1 ring-gray-200 max-h-[320px] w-full object-contain bg-gray-50`

Do not modify pipeline behavior.

## Deliverables

When you finish, provide:

- A list of files you changed and added.

Do not include diffs or full file content unless asked.

## Completion criteria

- All form pages under `resources/js/Pages/Admin/**` now share the same layout and control styling.
- The changes are purely presentational.
- The new shared styles are in the Tailwind component layer and are actually used by the admin pages.