<?php

namespace App\Models;

use App\Services\MarkdownRenderer;
use App\Support\Media\MediaUrlBuilder;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property Carbon|null $created_on
 */
class Artwork extends Model implements HasMedia
{
    use HasFactory, HasUniqueSlug, InteractsWithMedia;

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

    /**
     * Spatie's Media::toArray() includes `original_url`; keep the raw relation
     * out of serialized payloads. Use media_urls (via MediaUrlBuilder) instead.
     */
    protected $hidden = ['media'];

    public function mediaUrlBuilder(): MediaUrlBuilder
    {
        return new MediaUrlBuilder($this, 'artwork');
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->mediaUrlBuilder()->thumb();
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return app(MarkdownRenderer::class)->toHtml($this->description);
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

    /**
     * Admin-facing URL set. Never contains the private original; see MediaUrlBuilder.
     *
     * @return array<string, string|array{src: string, srcset: string}>
     */
    public function getMediaUrlsAttribute(): array
    {
        return $this->mediaUrlBuilder()->admin();
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

    public function galleries(): BelongsToMany
    {
        return $this->belongsToMany(Gallery::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    /**
     * Set this Artwork's galleries. Any gallery it leaves that used it as its
     * cover has the cover cleared, so a gallery never points at a non-member.
     *
     * @param  array<int, int|string>  $galleryIds
     */
    public function syncGalleries(array $galleryIds): void
    {
        $detached = $this->galleries()->sync($galleryIds)['detached'];

        if ($detached !== []) {
            Gallery::query()
                ->whereKey($detached)
                ->where('cover_artwork_id', $this->id)
                ->update(['cover_artwork_id' => null]);
        }
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('publish_status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured_flag', true);
    }

    /** Mature (nsfw_flag) artwork: gated on public pages (ADR-0004). */
    public function scopeMature(Builder $query): Builder
    {
        return $query->where('nsfw_flag', true);
    }

    /** Artwork that may appear ungated: the hero and SEO images (ADR-0004). */
    public function scopeNotMature(Builder $query): Builder
    {
        return $query->where('nsfw_flag', false);
    }
}
