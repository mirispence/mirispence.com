# Queue Jobs

Tags: #backend #jobs #queue

## `RegenerateArtworkImages`

File: `app/Jobs/RegenerateArtworkImages.php`

Implements `ShouldQueue`. Uses: `Dispatchable`, `InteractsWithQueue`, `Queueable`, `SerializesModels`.

### Purpose

Regenerates all Spatie Media Library conversion files for a given `Artwork`. Called when:
- A new artwork image is uploaded (in `ArtworkController::store`)
- An artwork image is replaced (in `ArtworkController::update`)
- Triggered manually via the admin `regenerate` or `bulkRegenerate` actions

### Constructor

```php
public function __construct(public Artwork $artwork)
```

The `Artwork` model is serialised (and deserialised on the worker) via `SerializesModels`.

### Execution Flow

1. Set `image_status = processing`, clear `image_error`
2. Retrieve the artwork's first media item from the `artwork` collection
3. If no media found, throw `\Exception('No media found for this artwork.')`
4. Call `Spatie\MediaLibrary\Conversions\FileManipulator::createDerivedFiles($media)`
   - This regenerates all conversions: `thumb`, `grid_640`, `grid_960`, `display_1280`, `display_1600`, `display_2048`
5. On success: set `image_status = ready`, `image_processed_at = now()`
6. On failure: set `image_status = failed`, `image_error = $e->getMessage()`, log error

### Resulting Conversions

See [[../Domains/Artwork#Media Collections & Conversions]] for the full list of conversion sizes.

### Error Handling

Errors are caught and written to `image_error`. The job does not re-throw, so the queue does not retry on application-level errors. Laravel logs the failure via `\Log::error(...)`.

### Dispatch Example

```php
RegenerateArtworkImages::dispatch($artwork);
```

For bulk operations, each artwork is dispatched individually in a loop.

---

## Related

- [[../Domains/Artwork]]
- [[../Infrastructure/Queue & Horizon]]
- [[../Backend/Controllers - Admin]]
