---
name: errorloop
description: Automate production error remediation through the ErrorLoop service. Trigger when investigating production exceptions, claiming issues, recording fix attempts, deploying fixes, or verifying resolutions.
---

# ErrorLoop

## Overview

ErrorLoop is a production error repair queue. This skill guides autonomous remediation of issues reported to an ErrorLoop service:

- Discover and inspect open production issues
- Claim an issue and investigate its context
- Implement a fix in the codebase
- Record the fix attempt and the deployed release
- Verify the fix resolved the issue

## Workflow Decision Tree

1. **Find work**: list open issues with `errorloop issues --status open`
2. **Inspect**: fetch the issue with `errorloop issue <id> --for-agent`
3. **Claim**: run `errorloop claim <id>` to take ownership
4. **Fix**: edit code, then record the attempt with `errorloop fix-attempted <id> --commit <sha> --agent <name>`
5. **Deploy**: when the fix ships, run `errorloop deploy --project <id> --sha <sha>`
6. **Verify**: after the verification window passes, run `errorloop verify <id>`

## Finding Issues

List the most recently seen open issues:

```bash
errorloop issues --status open
```

Filter by project if needed:

```bash
errorloop issues --status open --project-id 3
```

## Inspecting an Issue

Get concise, agent-oriented context:

```bash
errorloop issue 123 --for-agent
```

This returns the fingerprint, title, status, event count, releases, and the latest event example payload.

## Claiming an Issue

Only `open` or `regressed` issues can be claimed:

```bash
errorloop claim 123
```

## Recording a Fix Attempt

After modifying code, record the attempt before deployment:

```bash
errorloop fix-attempted 123 --commit abc123 --branch main --agent $(whoami) --notes "Fixed null pointer in BillingService"
```

## Recording a Deploy

When the commit that contains the fix is deployed, notify ErrorLoop:

```bash
errorloop deploy --project babyprocare --sha abc123 --environment production
```

A deploy matching a fix-attempt commit automatically moves the issue to `verifying`.

## Verifying a Fix

After the verification window (default 60 minutes) has passed with no recurrence:

```bash
errorloop verify 123
```

## Regression Handling

If the same fingerprint is seen again after an issue is `resolved`, ErrorLoop automatically reopens it as `regressed`. Reclaim it and fix it again.

## Resources

- `references/api_reference.md` — API and CLI reference
