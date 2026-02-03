<?php

namespace App\Support\Seo;

use App\Models\Artwork;
use App\Models\Book;
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
        $fullTitle = ($title === $appName || !$appendBrand) ? $title : "{$title} - {$appName}";
        
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
            description: "Portfolio of Miri Spence - Concept Art, Illustration, and Fantasy Books.",
            image: self::getFallbackOgImage()
        );
    }

    public static function forArtIndex(): SeoPayload
    {
        return self::make(
            title: 'Art',
            description: "Browse the art gallery of Miri Spence. Digital paintings, character designs, and illustrations.",
            image: self::getFallbackOgImage()
        );
    }

    public static function forArtwork(Artwork $artwork): SeoPayload
    {
        $description = $artwork->description ?: "View '{$artwork->title}' by Miri Spence.";
        $image = ($artwork->getMediaUrlsAttribute()['display']['src'] ?? null) ?: $artwork->getFirstMediaUrl('artwork');
        
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
            'url' => route('art.show', $artwork),
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
            image: $gallery->getImageUrlAttribute() ?: self::getFallbackOgImage()
        );
    }

    public static function forBooksIndex(): SeoPayload
    {
        return self::make(
            title: 'Books',
            description: "Explore the books and stories by Miri Spence. Fantasy novels, world-building, and more.",
            image: self::getFallbackOgImage()
        );
    }

    public static function forBook(Book $book): SeoPayload
    {
        $description = $book->description ?: "Read more about '{$book->title}' by Miri Spence.";
        $image = ($book->getMediaUrlsAttribute()['original'] ?? null) ?: $book->getFirstMediaUrl('cover');

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
            description: "Get in touch with Miri Spence for commissions, inquiries, or just to say hello.",
            image: self::getFallbackOgImage()
        );
    }

    protected static function normalizeDescription(?string $text): string
    {
        if (!$text) return '';
        
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
        return "The official website of Miri Spence, artist and author.";
    }

    protected static function getDefaultOgImage(?string $type): ?string
    {
        return self::getFallbackOgImage();
    }

    protected static function getFallbackOgImage(): ?string
    {
        // 1. First featured artwork
        $artwork = Artwork::published()->featured()->latest('created_on')->first();
        
        if ($artwork) {
            return ($artwork->getMediaUrlsAttribute()['display']['src'] ?? null) ?: $artwork->getFirstMediaUrl('artwork');
        }

        // 2. Otherwise, the first image in the artworks table
        $artwork = Artwork::published()->latest('created_on')->first();

        if ($artwork) {
            return ($artwork->getMediaUrlsAttribute()['display']['src'] ?? null) ?: $artwork->getFirstMediaUrl('artwork');
        }

        return null;
    }
}
