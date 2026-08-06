# Skill: Update Project Documentation & State

This skill guides an AI agent through updating the Obsidian documentation vault (`vault/documentation/`) and the project state files (`.ai/PROJECT_STATE.md`, `.ai/CONTEXT.md`) upon completing code changes.

---

## Trigger

Run this workflow whenever code has been modified, added, or deleted, and before finalizing the task.

---

## Workflow Steps

### Step 1: Analyze Your Code Changes

Run `git status` and `git diff` to identify all files that were added, modified, or deleted:

```bash
git status
```

### Step 2: Map Changes to Documentation Files

Identify which documentation files in `vault/documentation/` correspond to the changed codebase areas:

- **Models modified?** Update the corresponding domain files in `vault/documentation/Domains/` (e.g., `Artwork.md`, `Book & Chapter.md`).
- **Controllers or Jobs modified?** Update `vault/documentation/Backend/` (e.g., `Controllers - Admin.md`, `Controllers - Public.md`, `Jobs.md`).
- **Routes changed?** Update `vault/documentation/Routing/Routes.md`.
- **Auth or Permissions changed?** Update `vault/documentation/Infrastructure/Auth - Fortify.md` or `Permissions & Roles.md`.
- **Tests added or fixed?** Update `vault/documentation/Testing.md`.

### Step 3: Update Obsidian Vault Files

1. **Open and Edit**: Open each mapped file and update it with the new signatures, behaviors, config options, or route paths.
2. **Add New Files**: If a new module, model, or integration was created:
   - Create a new `.md` file in the appropriate subfolder of `vault/documentation/`.
   - Add it to the quick navigation list in `vault/documentation/00 - Index.md`.
3. **Clean Up Gaps**: If you resolved an issue, bug, or gap listed in `Testing.md` (under "Known Gaps"), remove or mark it as resolved.
4. **Link Formatting**: Use Obsidian-style wikilinks `[[Path/To/File]]` for internal navigation.

### Step 4: Update `.ai/` Project State & Context

Always keep the AI state files synchronized:

1. **Update Dates**: Change the `Last updated` date in both `.ai/PROJECT_STATE.md` and `.ai/CONTEXT.md` to the current date.
2. **What Is Working**:
   - Add new features, capabilities, and resolved tasks under the `What Is Working` section of `PROJECT_STATE.md`.
3. **What Is Broken or Missing / Known Issues**:
   - Remove resolved tasks, bug descriptions, or gaps from the lists in both `PROJECT_STATE.md` and `CONTEXT.md`.
   - Add any new follow-up tasks, unresolved constraints, or newly discovered bugs.

### Step 5: Final Verification

Ensure all markdown files are clean, links are valid, and code changes are fully documented.
