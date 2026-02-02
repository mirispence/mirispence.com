<?php

namespace App\Console\Commands\Media;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;

class Backfill extends Command
{
    protected $signature = 'media:backfill {--dry-run : Only show what would be moved} {--fix-only : Only fix DB records and move files between R2 buckets if they are in the wrong place}';

    protected $description = 'Migrate existing media from local storage to R2 and ensure they are on the correct buckets';

    public function handle(): int
    {
        $this->info('Starting media migration/verification...');

        $mediaItems = Media::all();
        $bar = $this->output->createProgressBar($mediaItems->count());

        $r2Private = Storage::disk('r2_private');
        $r2Public = Storage::disk('r2_public');
        $localPrivate = Storage::disk('media_private');
        $localPublic = Storage::disk('media_conversions');

        foreach ($mediaItems as $media) {
            // 1. Migrate Original
            $originalPath = $media->getPathRelativeToRoot();
            $this->migrateSingleFile($media, $originalPath, $localPrivate, $r2Private, 'Original');

            // 2. Migrate Conversions
            foreach ($media->getGeneratedConversions() as $conversionName => $status) {
                if ($status) {
                    $conversionPath = $media->getPathRelativeToRoot($conversionName);
                    
                    // Check if it's accidentally in r2_private from a previous run
                    if ($r2Private->exists($conversionPath)) {
                        $this->line("Found conversion on private bucket, moving to public: {$conversionPath}");
                        if (!$this->option('dry-run')) {
                            $r2Public->put($conversionPath, $r2Private->get($conversionPath));
                            $r2Private->delete($conversionPath);
                        }
                    } else {
                        // Try migrating from local
                        $this->migrateSingleFile($media, $conversionPath, $localPublic, $r2Public, "Conversion: {$conversionName}");
                    }
                }
            }

            // 3. Update disk names in DB
            if (!$this->option('dry-run')) {
                $media->disk = 'r2_private';
                $media->conversions_disk = 'r2_public';
                $media->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Process complete!');

        return 0;
    }

    protected function migrateSingleFile($media, $relativePath, $sourceDisk, $targetDisk, $type): void
    {
        if ($targetDisk->exists($relativePath)) {
            return; // Already migrated
        }

        if ($sourceDisk->exists($relativePath)) {
            if ($this->option('dry-run')) {
                $this->line("Would move {$type}: {$relativePath}");
                return;
            }

            $targetDisk->put($relativePath, $sourceDisk->get($relativePath));
            $this->line("Moved {$type}: {$relativePath}");
        } else {
            // Some files might be in the old non-uuid structure if CustomPathGenerator was added recently.
            // But if it's always been there, they should be in relativePath.
            $this->warn("File missing on local source: {$relativePath}");
        }
    }
}
