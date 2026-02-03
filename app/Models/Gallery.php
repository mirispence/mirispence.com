<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, \App\Traits\HasUniqueSlug;

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
     *
     * @return void
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
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media|null  $media
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->nonQueued()
            ->format('webp')
            ->quality(80)
            ->performOnCollections('gallery');
    }

    /**
     * Get the thumb URL attribute.
     *
     * @return string|null
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('gallery', 'thumb');
    }

    /**
     * Get the description HTML attribute.
     *
     * @return string
     */
    public function getDescriptionHtmlAttribute(): string
    {
        return app(\App\Services\MarkdownRenderer::class)->toHtml($this->description);
    }

    /**
     * Get the image URL attribute.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('gallery');
    }

    /**
     * Get the media URLs attribute.
     *
     * @return array
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
