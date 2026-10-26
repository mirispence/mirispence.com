<?php

namespace App\Http\Resources;

use App\Models\Artwork;
use App\Support\MatureConsent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Artwork
 */
class PublicArtworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Only exposes public CDN URLs - no private/original URLs. A flagged
     * (nsfw_flag) artwork carries no media URLs at all until the session has
     * consented to it (ADR-0004); `media_withheld` tells the page to gate it.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $withheld = ! app(MatureConsent::class)->allows($this->resource);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_html' => $this->description_html,
            'alt_text' => $this->alt_text,
            'created_on' => $this->created_on?->format('F Y'),
            'nsfw_flag' => $this->nsfw_flag,
            'media_withheld' => $withheld,
            'media_urls' => $withheld ? [] : $this->mediaUrlBuilder()->public(),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])),
            'galleries' => $this->whenLoaded('galleries', fn () => $this->galleries->map(fn ($gallery) => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'slug' => $gallery->slug,
            ])),
        ];
    }
}
