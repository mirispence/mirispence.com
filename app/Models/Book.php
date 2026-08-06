<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property \Illuminate\Support\Carbon|null $release_date
 */
class Book extends Model implements HasMedia
{
    use \App\Traits\HasUniqueSlug, HasFactory, InteractsWithMedia;

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
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->nonQueued()
            ->performOnCollections('cover')
            ->width(320)
            ->format('webp')
            ->quality(80);
    }

    /**
     * Get the thumb URL attribute.
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('cover', 'thumb');
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
        return $this->getFirstMediaUrl('cover');
    }

    /**
     * Get the media URLs attribute.
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
     */
    protected function casts(): array
    {
        return [
            'featured_flag' => 'boolean',
            'release_date' => 'date',
            'external_links' => 'array',
        ];
    }

    public function chapters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    /**
     * Get the tags relationship.
     */
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
