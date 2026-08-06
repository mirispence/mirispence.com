# Agent Protocol

## Required startup steps

1) Read:
   - .ai/PROJECT_STATE.md
   - .ai/GUARDRAILS.md
   - .ai/CONTEXT.md (if present)
2) Identify project type:
   - Laravel (confirm version and test style)
   - Non-Laravel (confirm toolchain from CONTEXT)
3) Create a git branch to do all work in

## Branching rules

- One branch per task.
- Branch naming:
  - `ai/<agent-name>/<task-id>-<slug>`
- Never work directly on main/master.

## Collision avoidance

- Do not modify files already touched by another CLAIMED task.
- If unavoidable, stop and coordinate by updating TASKS.

## Allowed scope

- Work only within the claimed task scope.
- No opportunistic fixes or refactors.

## Completion requirements

Each completed task must provide:

- Summary: what changed and why
- Files changed: list
- How to test: exact commands
- Risks or follow-ups
- Updated PROJECT_STATE and CONTEXT
- Updated Obsidian documentation vault files (see skill details in [.ai/skills/update_project_documentation.md](file:///d:/Programming/php/mirispence.com/.ai/skills/update_project_documentation.md))
