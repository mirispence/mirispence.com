---
name: update-project-documentation
description: Update the Obsidian documentation vault and root CONTEXT.md/CLAUDE.md after code changes. Use whenever code has been modified, added, or deleted, before finalizing a task.
---

# Update Project Documentation

Keep `vault/documentation/` and the root `CONTEXT.md` / `.claude/CLAUDE.md` in sync with the codebase after a change.

## Workflow

### 1. Identify what changed

```bash
git status
git diff
```

### 2. Map changes to documentation files

- **Models modified?** Update the corresponding domain files in `vault/documentation/Domains/` (e.g., `Artwork.md`, `Book & Chapter.md`).
- **Controllers or Jobs modified?** Update `vault/documentation/Backend/` (e.g., `Controllers - Admin.md`, `Controllers - Public.md`, `Jobs.md`).
- **Routes changed?** Update `vault/documentation/Routing/Routes.md`.
- **Auth or Permissions changed?** Update `vault/documentation/Infrastructure/Auth - Fortify.md` or `Permissions & Roles.md`.
- **Tests added or fixed?** Update `vault/documentation/Testing.md`.

### 3. Update the Obsidian vault

1. Open and edit each mapped file with the new signatures, behaviors, config options, or route paths.
2. New module, model, or integration? Create a new `.md` file in the appropriate subfolder of `vault/documentation/` and add it to the quick navigation list in `vault/documentation/00 - Index.md`.
3. Resolved an issue, bug, or gap listed in `Testing.md` under "Known Gaps"? Remove or mark it resolved.
4. Use Obsidian-style wikilinks `[[Path/To/File]]` for internal navigation.

### 4. Update `CONTEXT.md` and `.claude/CLAUDE.md`

- If repository layout, domains, or key relationships changed, update the relevant section of root `CONTEXT.md`.
- If the change resolves or introduces an item under `.claude/CLAUDE.md` § Known incomplete areas, update that list — it's the single source of truth for outstanding gaps (`CONTEXT.md` points to it rather than duplicating it).
- If the change is a hard-to-reverse architectural decision, consider adding an ADR under `docs/adr/`.

### 5. Final verification

Ensure all markdown files are clean, wikilinks resolve, and the code changes are fully documented.
