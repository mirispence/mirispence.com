<?php

use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

// #52: the shared ArtLightbox opens from a card on /artwork and /art/{gallery}.
// Its "View details" link is artworkShow.url(artwork.slug), so every artwork the
// page hands the lightbox must carry a slug that resolves to its detail page.

beforeEach(function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');
});

function lightboxArtwork(array $attributes = []): Artwork
{
    $artwork = Artwork::factory()->create(array_merge([
        'publish_status' => 'published',
        'nsfw_flag' => false,
    ], $attributes));
    $artwork->addMedia(UploadedFile::fake()->image('art.jpg', 400, 600))->toMediaCollection('artwork');

    return $artwork->fresh();
}

/** @return array<int, array<string, mixed>> */
function lightboxItems(TestResponse $response): array
{
    $props = $response->assertOk()->viewData('page')['props'];

    return json_decode((string) json_encode($props['artworks']), true)['data'];
}

function assertLightboxLinksResolve(TestResponse $response, int $expectedCount): void
{
    $items = lightboxItems($response);

    expect($items)->toHaveCount($expectedCount);

    foreach ($items as $item) {
        expect($item['slug'] ?? null)->toBeString()->not->toBeEmpty();
        test()->get(route('artwork.show', $item['slug']))->assertOk();
    }
}

test('every artwork on the artwork index links to a working detail page by slug', function () {
    // Ids and slugs never coincide here, so an id-based link would 404.
    $artworks = [
        lightboxArtwork(['title' => 'Blue Hour', 'slug' => 'blue-hour']),
        lightboxArtwork(['title' => 'Red Dawn', 'slug' => 'red-dawn']),
        lightboxArtwork(['title' => 'Mature', 'slug' => 'mature-piece', 'nsfw_flag' => true]),
    ];

    assertLightboxLinksResolve($this->get(route('artwork.index')), 3);

    foreach ($artworks as $artwork) {
        expect((string) $artwork->id)->not->toBe($artwork->slug);
        $this->get('/artwork/'.$artwork->id)->assertNotFound();
    }
});

test('every artwork on a gallery page links to a working detail page by slug', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $artworks = [
        lightboxArtwork(['title' => 'Blue Hour', 'slug' => 'blue-hour']),
        lightboxArtwork(['title' => 'Mature', 'slug' => 'mature-piece', 'nsfw_flag' => true]),
    ];
    $gallery->artworks()->attach(collect($artworks)->pluck('id'));

    assertLightboxLinksResolve($this->get(route('art.gallery', $gallery)), 2);

    foreach ($artworks as $artwork) {
        $this->get('/artwork/'.$artwork->id)->assertNotFound();
    }
});

test('flagged artwork in a gallery page hands the lightbox no media urls before consent', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $safe = lightboxArtwork();
    $flagged = lightboxArtwork(['nsfw_flag' => true]);
    $gallery->artworks()->attach([$safe->id, $flagged->id]);

    $response = $this->get(route('art.gallery', $gallery));
    $props = $response->viewData('page')['props'];

    assertNoMediaUrlsFor($props, $flagged);

    $byId = collect(lightboxItems($response))->keyBy('id');
    expect($byId[$flagged->id]['media_withheld'])->toBeTrue()
        ->and($byId[$safe->id]['media_withheld'])->toBeFalse()
        ->and($byId[$safe->id]['media_urls']['display']['src'] ?? null)->toBeString();
});
