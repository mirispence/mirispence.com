# Deployment

Tags: #infrastructure #deployment #ci-cd #github-actions

## Strategy

Capistrano-style timestamped releases via SSH, triggered by GitHub Actions.

- Each deploy creates a new timestamped directory under `releases/`
- Shared files (`.env`, `storage/`, `public/uploads/`) are symlinked from a `shared/` directory
- After deployment, the `current/` symlink is updated atomically
- Old releases are pruned (configurable; default keeps 5)

---

## Workflow File

`.github/workflows/deploy.yml`

### Triggers

- `workflow_dispatch` with `action: deploy` → manual deploy of the deploy branch's head; refused unless CI succeeded on that commit
- `workflow_dispatch` with `action: rollback` → rollback to previous release

Pushing or merging to `main` does not deploy. Start a deploy from the Actions tab or with `gh workflow run deploy.yml -f action=deploy`.

---

## Deploy Steps (in order)

1. Check that CI succeeded on the head of the deploy branch
2. **SSH into server** via `appleboy/ssh-action`
3. Load NVM and select default Node version
4. Create timestamped release directory under `$APP_ROOT/releases/$TS`
5. Verify `shared/.env` exists (fail fast if missing)
6. Clone repo at depth 1 from GitHub
7. Symlink `storage/` → `shared/storage/`
8. Symlink `public/uploads/` → `shared/uploads/`
9. Symlink `.env` → `shared/.env`
10. `composer install --no-dev --prefer-dist --optimize-autoloader`
11. `npm ci && npm run build`
12. `php artisan storage:link`
13. `php artisan migrate --force`
14. Clear and rebuild caches: `config`, `route`, `view`
15. `supervisorctl restart horizon`
16. Flip `current/` symlink to new release
17. `systemctl reload php8.4-fpm`
18. Prune old releases

---

## Rollback Steps

1. SSH into server
2. Find the second-most-recent release directory
3. Flip `current/` symlink to that release
4. Reload PHP-FPM

> Rollback does **not** run database migrations down. Schema changes must be backward-compatible.

---

## GitHub Secrets Required

| Secret | Description |
|---|---|
| `DEPLOY_HOST` | Server hostname/IP |
| `DEPLOY_USER` | SSH username |
| `DEPLOY_SSH_KEY` | Private SSH key |
| `DEPLOY_PORT` | SSH port (default 22) |
| `APP_ROOT` | Absolute path to app root on server |
| `DEPLOY_BRANCH` | Branch to deploy (default `main`) |
| `KEEP_RELEASES` | Number of releases to keep (default 5) |

---

## Server Requirements

- PHP 8.4-FPM
- Node.js (managed by NVM)
- Supervisor (for Horizon)
- Redis (for Horizon queue in production)
- Composer
- `sudo` access for `supervisorctl restart horizon` and `systemctl reload php8.4-fpm` (passwordless for deploy user)

---

## Known Gaps

- Rollback is manual (`workflow_dispatch`)

---

## Related

- [[Queue & Horizon]]
- [[../Architecture/Stack]]
