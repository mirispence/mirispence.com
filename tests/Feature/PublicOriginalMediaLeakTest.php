<?php

use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\ExpectationFailedException;

beforeEach(function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');
});

/**
 * A published, featured artwork with an image, plus its private original URL.
 *
 * @return array{0: Artwork, 1: string}
 */
function publishedArtworkWithImage(): array
{
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'featured_flag' => true]);
    $artwork->addMedia(UploadedFile::fake()->image('art.jpg', 400, 600))->toMediaCollection('artwork');
    $originalUrl = $artwork->getFirstMedia('artwork')->getUrl();

    expect($originalUrl)->not->toBe('');

    return [$artwork->fresh(), $originalUrl];
}

function inertiaProps(TestResponse $response): array
{
    return $response->viewData('page')['props'];
}

test('artwork index props and seo never expose the original', function () {
    [, $originalUrl] = publishedArtworkWithImage();

    $props = inertiaProps($this->get(route('artwork.index'))->assertOk());

    expect($props['artworks']['data'])->toHaveCount(1);
    assertNoOriginalMedia($props['artworks'], [$originalUrl]);
    assertNoOriginalMedia($props['seo'], [$originalUrl]);
});

test('artwork show props and seo og:image never expose the original', function () {
    [$artwork, $originalUrl] = publishedArtworkWithImage();

    $props = inertiaProps($this->get(route('artwork.show', $artwork))->assertOk());

    expect($props['seo']['og']['image'])->toBe($artwork->mediaUrlBuilder()->public()['display']['src']);
    assertNoOriginalMedia($props['artwork'], [$originalUrl]);
    assertNoOriginalMedia($props['seo'], [$originalUrl]);
});

test('gallery show props and seo never expose the original', function () {
    [$artwork, $originalUrl] = publishedArtworkWithImage();
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($artwork->id);
    $gallery->update(['cover_artwork_id' => $artwork->id]);

    $props = inertiaProps($this->get(route('art.gallery', $gallery))->assertOk());

    expect($props['artworks']['data'])->toHaveCount(1);
    assertNoOriginalMedia($props['gallery'], [$originalUrl]);
    assertNoOriginalMedia($props['artworks'], [$originalUrl]);
    assertNoOriginalMedia($props['seo'], [$originalUrl]);
});

test('home featured artworks and seo og:image never expose the original', function () {
    [, $originalUrl] = publishedArtworkWithImage();

    $props = inertiaProps($this->get(route('home'))->assertOk());

    expect($props['featuredArtworks'])->toHaveCount(1);
    assertNoOriginalMedia($props['featuredArtworks'], [$originalUrl]);
    assertNoOriginalMedia($props['seo'], [$originalUrl]);
});

test('artwork without an image falls back to the site og image, never an original url', function () {
    [$featured, $originalUrl] = publishedArtworkWithImage();
    $bare = Artwork::factory()->create(['publish_status' => 'published', 'featured_flag' => false]);

    $props = inertiaProps($this->get(route('artwork.show', $bare))->assertOk());

    expect($props['seo']['og']['image'])->toBe($featured->mediaUrlBuilder()->public()['display']['src']);
    assertNoOriginalMedia($props['seo'], [$originalUrl]);
});

test('the no-original helper catches a leaked original key and url', function () {
    expect(fn () => assertNoOriginalMedia(['a' => ['original' => 'x']]))
        ->toThrow(ExpectationFailedException::class);
    expect(fn () => assertNoOriginalMedia(['a' => ['b' => 'https://cdn/x/orig.jpg']], ['https://cdn/x/orig.jpg']))
        ->toThrow(ExpectationFailedException::class);
});
