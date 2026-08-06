<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property \Illuminate\Support\Carbon|null $created_on
 */
class Artwork extends Model implements HasMedia
{
    use \App\Traits\HasUniqueSlug, HasFactory, InteractsWithMedia;

    public function getSlugSourceField(): string
    {
        return 'title';
    }

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

    protected $appends = ['media_urls', 'thumb_url', 'image_status_label', 'publish_status_label', 'description_html'];

    protected $with = ['media'];

    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('artwork', 'thumb');
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return app(\App\Services\MarkdownRenderer::class)->toHtml($this->description);
    }

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('artwork')
            ->singleFile()
            ->useDisk('r2_private')
            ->storeConversionsOnDisk('r2_public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->nonQueued()
            ->performOnCollections('artwork')
            ->width(320)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('grid_640')
            ->performOnCollections('artwork')
            ->width(640)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('grid_960')
            ->performOnCollections('artwork')
            ->width(960)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('display_1280')
            ->performOnCollections('artwork')
            ->width(1280)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('display_1600')
            ->performOnCollections('artwork')
            ->width(1600)
            ->format('webp')
            ->quality(80);

        $this->addMediaConversion('display_2048')
            ->performOnCollections('artwork')
            ->width(2048)
            ->format('webp')
            ->quality(80);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('artwork');
    }

    public function getMediaUrlsAttribute(): array
    {
        $media = $this->getFirstMedia('artwork');
        if (! $media) {
            return [];
        }

        return [
            'original' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
            'grid' => [
                'src' => $media->getUrl('grid_640'),
                'srcset' => $media->getUrl('grid_640').' 640w, '.$media->getUrl('grid_960').' 960w',
            ],
            'display' => [
                'src' => $media->getUrl('display_1280'),
                'srcset' => $media->getUrl('display_1280').' 1280w, '.
                           $media->getUrl('display_1600').' 1600w, '.
                           $media->getUrl('display_2048').' 2048w',
            ],
        ];
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

    public function galleries(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Gallery::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('publish_status', 'published');
    }

    public function scopeFeatured(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('featured_flag', true);
    }
}
