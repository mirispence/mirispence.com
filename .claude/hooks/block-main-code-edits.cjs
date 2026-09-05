#!/usr/bin/env node
// PreToolUse hook (Edit|Write|MultiEdit): blocks code changes committed directly to main/master.
// Docs, vault notes, and .claude/ settings are still allowed on main.
'use strict';

const { execSync } = require('child_process');

const ALLOWED_PATTERNS = [
    /(^|[\\/])\.claude([\\/]|$)/,
    /(^|[\\/])docs([\\/]|$)/,
    /(^|[\\/])vault([\\/]|$)/,
    /\.md$/i,
];

function allow() {
    process.exit(0);
}

function deny(reason) {
    // Avoid process.exit() right after a stdout write: when stdout is piped,
    // exit() can fire before the write flushes and the hook output is lost.
    process.exitCode = 0;
    process.stdout.write(
        JSON.stringify({
            hookSpecificOutput: {
                hookEventName: 'PreToolUse',
                permissionDecision: 'deny',
                permissionDecisionReason: reason,
            },
        }),
    );
}

let input = '';
process.stdin.on('data', (chunk) => {
    input += chunk;
});

function normalize(p) {
    return p.replace(/\\/g, '/').toLowerCase();
}

process.stdin.on('end', () => {
    let payload;
    try {
        payload = JSON.parse(input);
    } catch {
        return allow();
    }

    const filePath = payload?.tool_input?.file_path;
    if (!filePath) return allow();

    let branch, repoRoot;
    try {
        branch = execSync('git rev-parse --abbrev-ref HEAD', {
            encoding: 'utf8',
            stdio: ['ignore', 'pipe', 'ignore'],
        }).trim();
        repoRoot = execSync('git rev-parse --show-toplevel', {
            encoding: 'utf8',
            stdio: ['ignore', 'pipe', 'ignore'],
        }).trim();
    } catch {
        return allow();
    }

    if (branch !== 'main' && branch !== 'master') return allow();

    // This hook only governs the repo's own working tree. Scratch files,
    // memory files, and anything else outside the repo aren't code in the
    // repo and were never in scope for this rule.
    const normalizedFile = normalize(filePath);
    const normalizedRoot = normalize(repoRoot);
    if (!normalizedFile.startsWith(normalizedRoot + '/')) return allow();

    if (ALLOWED_PATTERNS.some((pattern) => pattern.test(filePath))) {
        return allow();
    }

    deny(
        `Editing "${filePath}" is blocked on branch "${branch}". This repo's workflow requires code changes to happen on a task branch: create one (e.g. \`git checkout -b ai/<task>\`), make the change there, then open a PR. Docs, vault notes, and .claude/ settings are still editable on main.`,
    );
});
