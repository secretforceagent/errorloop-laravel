# ErrorLoop API & CLI Reference

## Authentication

- **Events endpoint**: project API key via `Authorization: Bearer <api_key>`
- **Agent endpoints**: agent token via `Authorization: Bearer <agent_token>`

## API Endpoints

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| POST | `/api/events` | project key | Ingest an exception event |
| GET | `/api/issues` | agent token | List issues |
| GET | `/api/issues/{id}` | agent token | Show issue details |
| GET | `/api/issues/{id}/examples` | agent token | List event examples |
| POST | `/api/issues/{id}/claim` | agent token | Claim an issue |
| POST | `/api/issues/{id}/fix-attempts` | agent token | Record a fix attempt |
| POST | `/api/deploys` | agent token | Record a deploy |

## Event Payload

```json
{
  "exception_class": "RuntimeException",
  "message": "Something went wrong",
  "release": "abc123",
  "top_frame": {
    "file": "/app/Services/Billing.php",
    "function": "charge"
  }
}
```

## CLI Commands

```bash
errorloop issues [--status=open] [--project-id=ID]
errorloop issue <id> [--for-agent]
errorloop claim <id>
errorloop fix-attempted <id> --commit <sha> [--branch <branch>] [--agent <name>] [--notes <notes>]
errorloop deploy --project <id|name> --sha <sha> [--environment=production]
errorloop verify <id>
```

## Issue Lifecycle

```
open → claimed → fix_attempted → deployed → verifying → resolved
resolved → regressed → open
```

A deploy whose release matches a fix-attempt `commit_sha` automatically transitions the issue to `verifying`. The verification window defaults to 60 minutes.
