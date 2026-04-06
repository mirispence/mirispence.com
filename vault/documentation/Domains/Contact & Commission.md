# Contact & Commission Domain

Tags: #domain #contact #commission

## Contact Flow

1. Visitor submits the contact form at `/contact`
2. `ContactController::store()` validates with `ContactRequest`
3. Rate limiting: max 5 attempts per IP per hour (`contact:{ip}`, decay 3600s)
4. `ContactMessage` record created with IP and user agent stored in `metadata`
5. If `type = commission`, a linked `CommissionRequest` record is also created
6. Visitor redirected back with success flash

---

## Model: `App\Models\ContactMessage`

File: `app/Models/ContactMessage.php`

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `name` | string | |
| `email` | string | |
| `subject` | string | |
| `message` | text | |
| `type` | string | `general` \| `commission` |
| `status` | string | Admin-managed read/action status |
| `metadata` | json | `{ ip, user_agent }` |

### Relationships

- `commissionRequest()` — `HasOne(CommissionRequest)`

### Validation (`ContactRequest`)

| Field | Rules |
|---|---|
| `name` | required, string, max:120 |
| `email` | required, email, max:255 |
| `subject` | required, string, max:150 |
| `message` | required, string, min:20, max:5000 |
| `type` | nullable, in:general,commission |
| `budget_range` | nullable, string, max:100 |
| `desired_due_date` | nullable, date |
| `honeypot` | **prohibited** (bot trap) |

---

## Model: `App\Models\CommissionRequest`

File: `app/Models/CommissionRequest.php`

### Fillable Fields

| Field | Type | Notes |
|---|---|---|
| `contact_message_id` | bigint FK | |
| `project_description` | text | |
| `budget_range` | string | |
| `desired_due_date` | date | |
| `status` | string | `new`, etc. |

> **Status:** Model, migration, and factory exist but there is **no admin controller, no routes, and no admin UI**. This is tracked as **TASK-003** in the backlog.

---

## Admin Operations

`ContactMessage` is read-only from the admin perspective (index, show, update status, destroy). There is no public creation admin route.

## Rate Limiting

Implemented directly in `ContactController::store()` using Laravel's `RateLimiter` facade:
- Key: `contact:{ip}`
- Max attempts: 5
- Decay: 3600 seconds (1 hour)
- Returns HTTP 429 on breach

## TODO

- Email notification on message submission is marked as a TODO in `ContactController::store()` (Phase 5)
- Commission request admin UI — see TASK-003

## Related

- [[../Backend/Controllers - Public]]
- [[../Backend/Controllers - Admin]]
- [[../Architecture/Database Schema]]
