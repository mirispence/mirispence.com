<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->singleFile()
            ->useDisk('r2_private')
            ->storeConversionsOnDisk('r2_public');
    }

    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('gallery', 'thumb');
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return app(\App\Services\MarkdownRenderer::class)->toHtml($this->description);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('gallery');
    }

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

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
