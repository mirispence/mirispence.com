# Queue & Horizon

Tags: #infrastructure #queue #horizon #redis

## Queue Driver

| Environment | Driver |
|---|---|
| Development | `database` (from `.env.example`) |
| Production | Redis (required by Horizon) |

Configured via `QUEUE_CONNECTION` in `.env`.

---

## Laravel Horizon

File: `config/horizon.php`

Horizon provides a dashboard for monitoring queue workers. Protected by `HorizonServiceProvider` which restricts dashboard access to authorised users.

In production, Horizon is managed by **Supervisor** (`supervisorctl restart horizon`), invoked as part of the deploy script.

---

## Jobs in Use

| Job | Queue | Notes |
|---|---|---|
| `RegenerateArtworkImages` | default | Triggered on artwork image upload/replace or manual regenerate |
| Spatie `PerformConversionsJob` | (Spatie internal) | Triggered by Media Library when media is added |

See [[../Backend/Jobs]] for `RegenerateArtworkImages` implementation details.

---

## Media Library Queue Configuration

From `config/media-library.php`:

```php
'queue_conversions_by_default'              => env('QUEUE_CONVERSIONS_BY_DEFAULT', true),
'queue_conversions_after_database_commit'   => env('QUEUE_CONVERSIONS_AFTER_DB_COMMIT', true),
```

The `thumb` conversion on `Artwork` is explicitly `->nonQueued()` — it runs synchronously at upload time to ensure an immediate thumbnail is available.

---

## Scheduled Tasks

Via `routes/console.php`. `spatie/laravel-sitemap` generates a sitemap daily (registered in the schedule).

---

## Related

- [[../Backend/Jobs]]
- [[../Infrastructure/Deployment]]
- [[../Domains/Artwork]]
