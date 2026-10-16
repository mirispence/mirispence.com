<?php

namespace App\Models;

use App\Services\MarkdownRenderer;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $release_date
 */
class Book extends Model
{
    use HasFactory, HasUniqueSlug;

    public function getSlugSourceField(): string
    {
        return 'title';
    }

    protected $fillable = [
        'title',
        'slug',
        'description',
        'publish_status',
        'featured_flag',
        'release_date',
        'external_links',
        'gallery_id',
    ];

    protected $appends = ['description_html'];

    /**
     * The Gallery relation is never serialized directly; use `cover`.
     */
    protected $hidden = ['gallery'];

    /**
     * Get the description HTML attribute.
     */
    public function getDescriptionHtmlAttribute(): string
    {
        return app(MarkdownRenderer::class)->toHtml($this->description);
    }

    /**
     * Append `cover` to the serialized form. Eager-load
     * `gallery.coverArtwork.media` first to avoid N+1 queries.
     */
    public function withCover(): static
    {
        return $this->append('cover');
    }

    /**
     * The Book's cover: its Gallery's public-safe cover URLs plus a link
     * target for that Gallery. Null when there is no Gallery, the Gallery is
     * not published (its show page would 404), or it has no visible cover.
     * Never contains an `original` key (see MediaUrlBuilder::public()).
     *
     * @return array{urls: array<string, mixed>, gallery: array{name: string, slug: string}}|null
     */
    public function getCoverAttribute(): ?array
    {
        $gallery = $this->gallery;

        if ($gallery === null || $gallery->publish_status !== 'published') {
            return null;
        }

        $urls = $gallery->cover_urls;

        if ($urls === []) {
            return null;
        }

        return [
            'urls' => $urls,
            'gallery' => [
                'name' => $gallery->name,
                'slug' => $gallery->slug,
            ],
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

    /**
     * The Gallery whose cover Artwork is this Book's cover.
     *
     * @return BelongsTo<Gallery, $this>
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    /**
     * Get the tags relationship.
     */
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
}
