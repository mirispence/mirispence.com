<?php

namespace App\Models;

use App\Services\MarkdownRenderer;
use App\Support\MatureConsent;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gallery extends Model
{
    use HasFactory, HasUniqueSlug;

    public function getSlugSourceField(): string
    {
        return 'name';
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'publish_status',
        'cover_artwork_id',
    ];

    /**
     * `cover_urls` is deliberately not appended globally: it needs the
     * coverArtwork relation, so callers eager-load `coverArtwork.media`
     * and append it explicitly (see `withCoverUrls()`).
     */
    protected $appends = ['description_html'];

    /**
     * The cover Artwork relation is never serialized directly; use `cover_urls`.
     */
    protected $hidden = ['coverArtwork', 'cover_is_member'];

    /**
     * Append `cover_urls` to the serialized form (relation should be eager-loaded).
     */
    public function withCoverUrls(): static
    {
        return $this->append('cover_urls');
    }

    /**
     * Get the description HTML attribute.
     */
    public function getDescriptionHtmlAttribute(): string
    {
        return app(MarkdownRenderer::class)->toHtml($this->description);
    }

    /**
     * Public-safe media URLs of the cover Artwork (never contains `original`).
     * Empty when there is no cover or the cover Artwork is not published,
     * so a draft artwork never surfaces through its gallery. Also empty for a
     * mature (nsfw_flag) cover the session has not consented to.
     * Eager-load `coverArtwork.media` when listing galleries to avoid N+1.
     *
     * @return array<string, mixed>
     */
    public function getCoverUrlsAttribute(): array
    {
        $cover = $this->coverArtwork;

        if ($cover === null || $cover->publish_status !== 'published' || ! $this->coverIsMember()) {
            return [];
        }

        // A mature cover stays withheld until the session consents (ADR-0004).
        if (! app(MatureConsent::class)->allows($cover)) {
            return [];
        }

        return $cover->mediaUrlBuilder()->public();
    }

    /**
     * Whether the cover Artwork is still one of this Gallery's artworks.
     * Uses the `cover_is_member` flag preloaded by `withPublicCover()` when present.
     */
    public function coverIsMember(): bool
    {
        if ($this->cover_artwork_id === null) {
            return false;
        }

        if (array_key_exists('cover_is_member', $this->attributes)) {
            return (bool) $this->attributes['cover_is_member'];
        }

        return $this->artworks()->whereKey($this->cover_artwork_id)->exists();
    }

    /**
     * The explicitly chosen cover Artwork, independent of artwork_gallery.sort_order.
     *
     * @return BelongsTo<Artwork, $this>
     */
    public function coverArtwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'cover_artwork_id');
    }

    /**
     * Get the artworks relationship.
     *
     * @return BelongsToMany<Artwork, $this>
     */
    public function artworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    /**
     * Eager-load everything `cover_urls` needs (cover artwork + media and the
     * cover-membership flag) and append it to every retrieved Gallery.
     */
    public function scopeWithPublicCover(Builder $query): Builder
    {
        return $query
            ->with('coverArtwork.media')
            ->withExists(['artworks as cover_is_member' => fn (Builder $artworks) => $artworks
                ->whereColumn('artworks.id', 'galleries.cover_artwork_id')])
            ->afterQuery(fn ($galleries) => $galleries->each(fn (Gallery $gallery) => $gallery->withCoverUrls()));
    }

    /**
     * Admin-only cover thumbnail (shown even when the cover is a draft).
     */
    public function getCoverThumbUrlAttribute(): string
    {
        return $this->coverArtwork->thumb_url ?? '';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('publish_status', 'published');
    }
}
