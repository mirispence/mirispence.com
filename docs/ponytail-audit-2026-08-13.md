# Over-engineering audit (ponytail-audit, 2026-08-13)

Repo-wide scan for dead code, speculative flexibility, and unnecessary
abstraction. Correctness, security, and performance are out of scope — see
a normal code review for those. Nothing here has been applied; this is a
findings list only.

Ranked biggest cut first.

1. `yagni:` Drop the dual `app.ts`/`admin.ts` Vite entry points + server-side
   bundle sniffing in `resources/views/app.blade.php:49-53`. Replacement:
   single entry point — Inertia's `import.meta.glob` resolver already
   lazy-loads per-page. Files: `vite.config.ts`, `resources/js/admin.ts`.
2. `yagni:` Drop full SSR (`config/inertia.php` `ssr.enabled`,
   `resources/js/ssr.ts`) — its page glob renders authenticated admin CMS
   pages server-side too, but SSR only pays off for public/SEO pages on a
   single-tenant personal site. Replacement: CSR only, or scope the SSR
   resolver to public pages.
3. `delete:` `resources/js/Layouts/auth/AuthSplitLayout.vue` — unused, only
   `AuthSimpleLayout.vue` is wired up.
4. `delete:` `resources/js/Layouts/auth/AuthCardLayout.vue` — unused, same
   reason.
5. `delete:` `resources/js/components/ui/collapsible/` (4 files) — no
   imports anywhere.
6. `delete:` `resources/js/components/ui/navigation-menu/{NavigationMenuContent,NavigationMenuIndicator,NavigationMenuLink,NavigationMenuTrigger,NavigationMenuViewport}.vue`
   — only `NavigationMenu`/`Item`/`List` are used, in `AppHeader.vue`.
7. `delete:` `resources/js/components/ui/card/CardAction.vue` — exported
   but never used.
8. `delete:` `'staff'` role check — `resources/views/app.blade.php:51` —
   role is never seeded/defined anywhere.
9. `delete:` `routes/ai.php` — entire file is one commented-out
   `Mcp::web(...)` line, no live routes.
10. `delete:` Commented-out notification-routing lines —
    `app/Providers/HorizonServiceProvider.php:17-19`.
11. `delete:` `HandleInertiaRequests::version()` override — just calls
    `parent::version($request)`, pure boilerplate —
    `app/Http/Middleware/HandleInertiaRequests.php:26-29`.
12. `yagni:` Collapse identical Store/Update Form Request pairs into one
    class each: `Chapter`, `FeaturedItem`, `Gallery`, `Tag` requests are
    byte-for-byte identical apart from class name —
    `app/Http/Requests/Admin/`.
13. `shrink:` `StoreArtworkRequest`/`UpdateArtworkRequest` — identical
    `rules()`, only `authorize()` ability string differs; merge into one
    class keyed off `isMethod('POST')`.
14. `shrink:` `StoreBookRequest`/`UpdateBookRequest` — same pattern.

## Note on CommissionRequest

Already known as an incomplete area (see `CONTEXT.md`). Confirmed not
fully dead: it has a real write path (`ContactController::store`) and a
read-only display (`resources/js/pages/Admin/Messages/Show.vue:80-134`).
Still oversized for a feature with no admin action workflow (no
status-change UI, no quote-sending flow) — treat as part of that existing
finding rather than a new one.

## Net

-8 unused files, -6 collapsible Form Request classes, -1 dual-entry Vite
config, possible SSR removal (-1 build target, -1 runtime process class of
infra).
