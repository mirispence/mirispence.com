<?php

namespace App\Support\Media;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Conversions\ConversionCollection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Single place that builds a media model's URL sets.
 *
 * Shape is derived from the conversions registered on the collection:
 *  - a plain conversion name (e.g. `thumb`) maps to its URL string;
 *  - width-suffixed conversions (e.g. `grid_640`, `grid_960`) are grouped
 *    under their prefix as `['src' => smallest, 'srcset' => '... 640w, ... 960w']`.
 *
 * Invariant: neither public() nor admin() ever exposes an `original` key.
 * The original file is only reachable through the gated
 * `admin.media.original` route.
 */
final class MediaUrlBuilder
{
    private const FORBIDDEN_KEY = 'original';

    public function __construct(
        private readonly HasMedia $model,
        private readonly string $collection,
    ) {}

    /**
     * URLs safe to serialize on public pages.
     *
     * @return array<string, string|array{src: string, srcset: string}>
     */
    public function public(): array
    {
        return $this->build();
    }

    /**
     * URLs for admin pages. Same shape as public(); still never contains a raw
     * original URL — link to the `admin.media.original` route instead.
     *
     * @return array<string, string|array{src: string, srcset: string}>
     */
    public function admin(): array
    {
        return $this->build();
    }

    /**
     * @return array<string, string|array{src: string, srcset: string}>
     */
    private function build(): array
    {
        $media = $this->model->getFirstMedia($this->collection);

        if (! $media instanceof Media) {
            return [];
        }

        $plain = [];
        $sized = [];

        $names = ConversionCollection::createForMedia($media)
            ->filter(fn (Conversion $conversion) => $conversion->shouldBePerformedOn($this->collection))
            ->map(fn (Conversion $conversion) => $conversion->getName());

        foreach ($names as $name) {
            if (preg_match('/^(.+)_(\d+)$/', $name, $m)) {
                $sized[$m[1]][(int) $m[2]] = $media->getUrl($name);
            } else {
                $plain[$name] = $media->getUrl($name);
            }
        }

        $urls = $plain;

        foreach ($sized as $prefix => $byWidth) {
            ksort($byWidth);
            $urls[$prefix] = [
                'src' => reset($byWidth),
                'srcset' => implode(', ', array_map(
                    fn (int $width, string $url) => "{$url} {$width}w",
                    array_keys($byWidth),
                    $byWidth,
                )),
            ];
        }

        unset($urls[self::FORBIDDEN_KEY]);

        return $urls;
    }
}
