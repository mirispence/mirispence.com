<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicArtworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Only exposes public CDN URLs - no private/original URLs.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $media = $this->getFirstMedia('artwork');

        // Build media_urls with ONLY public conversion URLs
        $mediaUrls = [];
        if ($media) {
            $mediaUrls = [
                'thumb' => $media->getUrl('thumb'),
                'grid' => [
                    'src' => $media->getUrl('grid_640'),
                    'srcset' => $media->getUrl('grid_640') . ' 640w, ' . $media->getUrl('grid_960') . ' 960w',
                ],
                'display' => [
                    'src' => $media->getUrl('display_1280'),
                    'srcset' => $media->getUrl('display_1280') . ' 1280w, ' .
                               $media->getUrl('display_1600') . ' 1600w, ' .
                               $media->getUrl('display_2048') . ' 2048w',
                ],
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_html' => $this->description_html,
            'alt_text' => $this->alt_text,
            'created_on' => $this->created_on?->format('F Y'),
            'nsfw_flag' => $this->nsfw_flag,
            'media_urls' => $mediaUrls,
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])),
            'galleries' => $this->whenLoaded('galleries', fn() => $this->galleries->map(fn($gallery) => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'slug' => $gallery->slug,
            ])),
        ];
    }
}
