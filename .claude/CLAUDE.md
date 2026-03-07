# Agent Protocol

## Required startup steps

Read:
- .ai/PROJECT_STATE.md
- .ai/TASKS.md
- .ai/GUARDRAILS.md
- .ai/CONTEXT.md (if present)

Tasks:
- If the task assigned exists in .ai/TASKS.md claim it before coding.
- If the task does not exist, place it in .ai/TASKS.md and then claim it before coding.

## Branching rules
- One branch per task.
- Suggested Branch naming:
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
- Updated PROJECT_STATE and TASKS
