<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Artwork extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'alt_text',
        'created_on',
        'publish_status',
        'nsfw_flag',
        'featured_flag',
        'metadata',
        'image_status',
        'image_error',
        'image_processed_at',
    ];

    protected $appends = ['image_url', 'image_status_label', 'signed_urls', 'publish_status_label'];

    protected $with = ['media'];

    protected function casts(): array
    {
        return [
            'created_on' => 'date',
            'nsfw_flag' => 'boolean',
            'featured_flag' => 'boolean',
            'metadata' => 'array',
            'image_processed_at' => 'datetime',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->nonQueued()
            ->keepOriginalImageFormat()
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');

        $this->addMediaConversion('grid_640')
            ->width(640)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');

        $this->addMediaConversion('grid_960')
            ->width(960)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');

        $this->addMediaConversion('display_1280')
            ->width(1280)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');

        $this->addMediaConversion('display_1600')
            ->width(1600)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');

        $this->addMediaConversion('display_2048')
            ->width(2048)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('artwork');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('artwork');
    }

    public function getImageStatusLabelAttribute(): string
    {
        return match ($this->image_status) {
            'ready' => 'Ready',
            'processing' => 'Processing',
            'failed' => 'Failed',
            default => 'Pending',
        };
    }

    public function getPublishStatusLabelAttribute(): string
    {
        return ucfirst($this->publish_status ?? 'draft');
    }

    public function getSignedUrlsAttribute(): array
    {
        $media = $this->getFirstMedia('artwork');
        
        if (!$media) {
            return [];
        }

        return [
            'grid' => [
                'src' => \App\Services\SignedMediaUrl::url($media, 'grid_640'),
                'srcset' => \App\Services\SignedMediaUrl::url($media, 'grid_640') . ' 640w, ' .
                           \App\Services\SignedMediaUrl::url($media, 'grid_960') . ' 960w',
            ],
            'display' => [
                'src' => \App\Services\SignedMediaUrl::url($media, 'display_1280'),
                'srcset' => \App\Services\SignedMediaUrl::url($media, 'display_1280') . ' 1280w, ' .
                           \App\Services\SignedMediaUrl::url($media, 'display_1600') . ' 1600w, ' .
                           \App\Services\SignedMediaUrl::url($media, 'display_2048') . ' 2048w',
            ],
            'original' => auth()->check() ? route('admin.media.original', $media->id) : null,
        ];
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
