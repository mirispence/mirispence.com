<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Model implements HasMedia
{
    use \App\Traits\HasUniqueSlug, HasFactory, InteractsWithMedia;

    public function getSlugSourceField(): string
    {
        return 'name';
    }

    protected $with = ['media'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'publish_status',
    ];

    protected $appends = ['media_urls', 'image_url', 'thumb_url', 'description_html'];

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->singleFile()
            ->useDisk('r2_private')
            ->storeConversionsOnDisk('r2_public');
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->nonQueued()
            ->performOnCollections('gallery')
            ->width(320)
            ->format('webp')
            ->quality(80);
    }

    /**
     * Get the thumb URL attribute.
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('gallery', 'thumb');
    }

    /**
     * Get the description HTML attribute.
     */
    public function getDescriptionHtmlAttribute(): string
    {
        return app(\App\Services\MarkdownRenderer::class)->toHtml($this->description);
    }

    /**
     * Get the image URL attribute.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('gallery');
    }

    /**
     * Get the media URLs attribute.
     */
    public function getMediaUrlsAttribute(): array
    {
        $media = $this->getFirstMedia('gallery');
        if (! $media) {
            return [];
        }

        return [
            'original' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
        ];
    }

    /**
     * Get the artworks relationship.
     */
    public function artworks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Artwork::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('publish_status', 'published');
    }
}
