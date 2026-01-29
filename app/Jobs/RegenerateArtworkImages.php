<?php

namespace App\Jobs;

use App\Models\Artwork;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RegenerateArtworkImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Artwork $artwork)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->artwork->update([
            'image_status' => 'processing',
            'image_error' => null,
        ]);

        try {
            $media = $this->artwork->getFirstMedia('artwork');
            
            if (!$media) {
                throw new \Exception('No media found for this artwork.');
            }

            // Manually trigger regeneration of all conversions
            // Spatie media library has a command, but we can do it via the service
            app(\Spatie\MediaLibrary\Conversions\FileManipulator::class)->createDerivedFiles($media);

            $this->artwork->update([
                'image_status' => 'ready',
                'image_processed_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->artwork->update([
                'image_status' => 'failed',
                'image_error' => $e->getMessage(),
            ]);
            
            \Log::error("Regeneration failed for Artwork {$this->artwork->id}: " . $e->getMessage());
        }
    }
}
