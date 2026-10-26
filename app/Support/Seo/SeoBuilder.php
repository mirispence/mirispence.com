<?php

namespace App\Support\Seo;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;
use Illuminate\Support\Str;

class SeoBuilder
{
    public static function make(
        string $title,
        ?string $description = null,
        ?string $type = 'website',
        ?string $image = null,
        ?string $imageAlt = null,
        ?array $jsonld = null,
        string $robots = 'index,follow',
        bool $appendBrand = true
    ): SeoPayload {
        $appName = config('app.name');
        $fullTitle = ($title === $appName || ! $appendBrand) ? $title : "{$title} - {$appName}";

        $canonical = url()->current();

        // Strip query strings for canonical unless they are meaningful
        // For this site, we generally want clean URLs
        if ($pos = strpos($canonical, '?')) {
            $canonical = substr($canonical, 0, $pos);
        }

        $description = self::normalizeDescription($description ?? self::getDefaultDescription());

        $og = [
            'type' => $type,
            'image' => $image ?? self::getDefaultOgImage($type),
        ];

        if ($imageAlt) {
            $og['image:alt'] = $imageAlt;
        }

        $twitter = [
            'image' => $og['image'],
        ];

        return new SeoPayload(
            title: $fullTitle,
            description: $description,
            canonical: $canonical,
            robots: $robots,
            og: $og,
            twitter: $twitter,
            jsonld: $jsonld,
            appendBrand: $appendBrand
        );
    }

    public static function forHome(): SeoPayload
    {
        return self::make(
            title: config('app.name'),
            description: 'Portfolio of Miri Spence - Concept Art, Illustration, and Fantasy Books.',
            image: self::getFallbackOgImage()
        );
    }

    public static function forArtIndex(): SeoPayload
    {
        return self::make(
            title: 'Art',
            description: 'Browse the art gallery of Miri Spence. Digital paintings, character designs, and illustrations.',
            image: self::getFallbackOgImage()
        );
    }

    public static function forArtworkIndex(): SeoPayload
    {
        return self::make(
            title: 'Artwork',
            description: 'Every artwork by Miri Spence. Digital paintings, character designs, and illustrations.',
            image: self::getFallbackOgImage()
        );
    }

    public static function forArtwork(Artwork $artwork): SeoPayload
    {
        $description = $artwork->description ?: "View '{$artwork->title}' by Miri Spence.";
        // Mature artwork never feeds SEO images (ADR-0004).
        $image = ($artwork->nsfw_flag ? null : self::publicDisplayUrl($artwork)) ?? self::getFallbackOgImage();

        $jsonld = [
            '@context' => 'https://schema.org',
            '@type' => 'VisualArtwork',
            'name' => $artwork->title,
            'description' => self::normalizeDescription($description),
            'image' => $image,
            'creator' => [
                '@type' => 'Person',
                'name' => config('app.name'),
            ],
            'url' => route('artwork.show', $artwork),
        ];

        if ($artwork->created_on) {
            $jsonld['dateCreated'] = $artwork->created_on->toIso8601String();
        }

        return self::make(
            title: "{$artwork->title} - Art",
            description: $description,
            type: 'article',
            image: $image,
            imageAlt: $artwork->alt_text ?? "Artwork '{$artwork->title}' by Miri Spence",
            jsonld: $jsonld,
            appendBrand: false
        );
    }

    public static function forGallery(Gallery $gallery): SeoPayload
    {
        return self::make(
            title: "{$gallery->name} - Art",
            description: $gallery->description ?: "View the {$gallery->name} gallery by Miri Spence.",
            image: self::galleryCoverImage($gallery) ?? self::getFallbackOgImage()
        );
    }

    public static function forBooksIndex(): SeoPayload
    {
        return self::make(
            title: 'Books',
            description: 'Explore the books and stories by Miri Spence. Fantasy novels, world-building, and more.',
            image: self::getFallbackOgImage()
        );
    }

    public static function forBook(Book $book): SeoPayload
    {
        $description = $book->description ?: "Read more about '{$book->title}' by Miri Spence.";
        // `cover` is null unless the gallery is published and has a visible cover.
        $image = ($book->cover !== null && $book->gallery ? self::galleryCoverImage($book->gallery) : null)
            ?? self::getFallbackOgImage();

        $jsonld = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'description' => self::normalizeDescription($description),
            'image' => $image,
            'author' => [
                '@type' => 'Person',
                'name' => config('app.name'),
            ],
            'url' => route('books.show', $book),
        ];

        if ($book->release_date) {
            $jsonld['datePublished'] = $book->release_date->toIso8601String();
        }

        return self::make(
            title: "{$book->title} - Books",
            description: $description,
            type: 'book',
            image: $image,
            jsonld: $jsonld,
            appendBrand: false
        );
    }

    public static function forContact(): SeoPayload
    {
        return self::make(
            title: 'Contact',
            description: 'Get in touch with Miri Spence for commissions, inquiries, or just to say hello.',
            image: self::getFallbackOgImage()
        );
    }

    protected static function normalizeDescription(?string $text): string
    {
        if (! $text) {
            return '';
        }

        // Replace HTML tags with spaces to avoid merging words
        $text = preg_replace('/<[^>]+>/', ' ', $text);

        // Strip URLs from markdown links [text](url) -> [text]
        $text = preg_replace('/\]\([^\)]+\)/', ']', $text);

        // Strip other markdown-style characters
        $text = preg_replace('/(\*\*|__|`|#|>|\[|\]|\(|\))/', '', $text);

        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        return Str::limit(trim($text), 160);
    }

    protected static function getDefaultDescription(): string
    {
        return 'The official website of Miri Spence, artist and author.';
    }

    protected static function getDefaultOgImage(?string $type): ?string
    {
        return self::getFallbackOgImage();
    }

    /**
     * Site-wide og:image: the latest featured published artwork, else the latest
     * published artwork, never a mature (nsfw_flag) one. Only ever a public
     * conversion URL, never the original.
     */
    protected static function getFallbackOgImage(): ?string
    {
        $safe = fn () => Artwork::published()->where('nsfw_flag', false)->latest('created_on');

        $artwork = $safe()->featured()->first() ?? $safe()->first();

        return self::publicDisplayUrl($artwork);
    }

    /**
     * The gallery's cover display URL, or null when it has none or the cover is
     * mature (consent never changes SEO output).
     */
    private static function galleryCoverImage(Gallery $gallery): ?string
    {
        if ($gallery->coverArtwork?->nsfw_flag) {
            return null;
        }

        $src = $gallery->cover_urls['display']['src'] ?? null;

        return is_string($src) && $src !== '' ? $src : null;
    }

    private static function publicDisplayUrl(?Artwork $artwork): ?string
    {
        $src = $artwork?->mediaUrlBuilder()->public()['display']['src'] ?? null;

        return is_string($src) && $src !== '' ? $src : null;
    }
}
