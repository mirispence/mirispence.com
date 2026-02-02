<?php

namespace App\Console\Commands\Media;

use Illuminate\Console\Command;

class Backfill extends Command
{
    protected $signature = 'media:backfill {--dry-run : Only show what would be moved}';

    protected $description = 'Migrate existing media from local storage to R2';

    public function handle(): int
    {
        $this->info('Starting media migration to R2...');

        $mediaItems = \Spatie\MediaLibrary\MediaCollections\Models\Media::all();
        $bar = $this->output->createProgressBar($mediaItems->count());

        $privateDisk = \Storage::disk('r2_private');
        $publicDisk = \Storage::disk('r2_public');
        $localPrivate = \Storage::disk('media_private');
        $localPublic = \Storage::disk('media_conversions');

        foreach ($mediaItems as $media) {
            $this->migrateFile($media, '', $localPrivate, $privateDisk);

            // Migrate conversions
            foreach ($media->getGeneratedConversions() as $conversionName => $status) {
                if ($status) {
                    $this->migrateFile($media, $conversionName, $localPublic, $publicDisk);
                }
            }

            // Update disk names in DB
            if (! $this->option('dry-run')) {
                $media->disk = 'r2_private';
                $media->conversions_disk = 'r2_public';
                $media->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migration complete!');

        return 0;
    }

    protected function migrateFile($media, $conversion, $sourceDisk, $targetDisk): void
    {
        $path = $media->getPath($conversion);
        $relativePath = $media->id.($conversion ? '/'.$conversion : '').'/'.$media->file_name;

        // Path generator adjustment if needed, but getPath() is usually absolute.
        // Spatie uses a specific structure. Let's try to find the relative path from the disk root.

        if ($sourceDisk->exists($relativePath)) {
            if ($this->option('dry-run')) {
                $this->line("Would move: {$relativePath}");

                return;
            }

            $targetDisk->put($relativePath, $sourceDisk->get($relativePath));
            $this->line("Moved: {$relativePath}");
        } else {
            $this->error("File missing: {$relativePath}");
        }
    }
}
