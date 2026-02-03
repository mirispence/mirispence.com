<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, \App\Traits\HasUniqueSlug;

    public function getSlugSourceField(): string
    {
        return 'title';
    }

    protected $with = ['media'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'publish_status',
        'featured_flag',
        'release_date',
        'external_links',
    ];

    protected $appends = ['media_urls', 'image_url', 'thumb_url', 'description_html'];
        
    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
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
            ->performOnCollections('cover');
    }

    /**
     * Get the thumb URL attribute.
     *
     * @return string|null
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('cover', 'thumb');
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
        return $this->getFirstMediaUrl('cover');
    }

    /**
     * Get the media URLs attribute.
     *
     * @return array
     */
    public function getMediaUrlsAttribute(): array
    {
        $media = $this->getFirstMedia('cover');
        if (! $media) {
            return [];
        }

        return [
            'original' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
        ];
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'featured_flag' => 'boolean',
            'release_date' => 'date',
            'external_links' => 'array',
        ];
    }



        /**
     * Get the chapters relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    /**
     * Get the tags relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('publish_status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured_flag', true);
    }
}
