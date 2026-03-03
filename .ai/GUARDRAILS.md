# Guardrails

These rules are always in effect unless a task explicitly overrides them.

## Critical (must not be violated)

- No secrets in code, state files, logs, commits, or tests.
- Changes to logic, validation, orchestration, or security boundaries require tests.
- One task per branch. One branch per task.
- Do not change behavior outside the task scope.

## Important (default expectations)

- Keep diffs small and scoped to the task.
- No drive-by refactors unless explicitly allowed by the task.
- Every change must report:
  - Files changed
  - How to test (exact commands)

## Progress tracking

- Update `.ai/PROJECT_STATE.md` and `.ai/TASKS.md` before marking a task DONE.
- If progress stalls, update task status to BLOCKED with a reason.

## Git rules

- Do not run git commands unless explicitly instructed by the user or task.
- Do not merge into main/master without explicit instruction.
- Do not use:
  - `git push --force`
  - `git merge --squash`
  - `git rebase`
  - `git commit --amend`
    unless explicitly instructed and you understand the consequences.
- If unsure, stop and ask.

## Laravel guardrails (Laravel projects only)

- Follow Laravel conventions before inventing abstractions.
- Validation:
  - HTTP: Form Requests
  - Non-HTTP (jobs, imports, CLI): dedicated validators or services
- Database access:
  - Prefer Eloquent or query builder
  - Raw SQL only when necessary, parameterized, and justified
- Prefer dependency injection and testable services over static calls.

## Laravel test stance

- Default test command: `php artisan test`
- HTTP behavior changes require Feature tests.
- Pure logic changes require Unit tests.
- Queue/job changes must test:
  - Dispatch
  - Payload shape
  - Failure paths

## Laravel quality tools (optional)

- Formatter: `./vendor/bin/pint` if present
- Static analysis: `./vendor/bin/phpstan` or `./vendor/bin/psalm` if present
- Do not introduce new tools unless instructed.
- If not configured, note "not configured" in task output.

## Version compatibility

- Laravel 10, 11, and 12 follow the same protocol.
- Do not migrate test frameworks (PHPUnit <-> Pest) unless explicitly tasked.
