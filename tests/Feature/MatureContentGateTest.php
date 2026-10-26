<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

beforeEach(function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');
});

function gateArtwork(array $attributes = []): Artwork
{
    $artwork = Artwork::factory()->create(array_merge([
        'publish_status' => 'published',
        'featured_flag' => true,
        'nsfw_flag' => false,
    ], $attributes));
    $artwork->addMedia(UploadedFile::fake()->image('art.jpg', 400, 600))->toMediaCollection('artwork');

    return $artwork->fresh();
}

function gateFlaggedArtwork(array $attributes = []): Artwork
{
    return gateArtwork(array_merge(['nsfw_flag' => true], $attributes));
}

function gateProps(TestResponse $response): array
{
    return $response->assertOk()->viewData('page')['props'];
}

/** @return array<string, mixed> the serialized artwork with the given id, found at any depth */
function gateFind(array $payload, int $id): array
{
    $found = null;

    $walk = function (mixed $value) use (&$walk, &$found, $id): void {
        if (! is_array($value) || $found !== null) {
            return;
        }
        if (($value['id'] ?? null) === $id && array_key_exists('nsfw_flag', $value)) {
            $found = $value;

            return;
        }
        foreach ($value as $child) {
            $walk($child);
        }
    };
    $walk(json_decode((string) json_encode($payload), true));

    expect($found)->not->toBeNull("Artwork {$id} not found in payload");

    return $found;
}

test('flagged artwork props on the art index carry no media urls before consent', function () {
    $flagged = gateFlaggedArtwork();

    $props = gateProps($this->get(route('artwork.index')));

    assertNoMediaUrlsFor($props, $flagged);
    $serialized = gateFind($props, $flagged->id);
    expect($serialized)->toMatchArray([
        'id' => $flagged->id,
        'slug' => $flagged->slug,
        'title' => $flagged->title,
        'alt_text' => $flagged->alt_text,
        'nsfw_flag' => true,
    ]);
});

function gateGalleryCoveredBy(Artwork $artwork): Gallery
{
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($artwork->id);
    $gallery->update(['cover_artwork_id' => $artwork->id]);

    return $gallery;
}

test('flagged artwork carries no media urls on gallery show, home and detail before consent', function () {
    $flagged = gateFlaggedArtwork();
    $gallery = gateGalleryCoveredBy($flagged);

    foreach ([
        route('art.gallery', $gallery),
        route('home'),
        route('artwork.show', $flagged),
    ] as $url) {
        $props = gateProps($this->get($url));

        assertNoMediaUrlsFor($props, $flagged);
        expect(gateFind($props, $flagged->id))->toMatchArray([
            'slug' => $flagged->slug,
            'title' => $flagged->title,
            'nsfw_flag' => true,
            'media_withheld' => true,
        ]);
    }
});

test('a gallery whose cover is flagged shows no cover urls before consent', function () {
    $flagged = gateFlaggedArtwork();
    gateGalleryCoveredBy($flagged);

    $props = gateProps($this->get(route('art.index')));

    assertNoMediaUrlsFor($props, $flagged);
    expect($props['galleries']['data'][0]['cover_urls'])->toBe([]);
});

test('revealing one flagged artwork exposes only that artwork', function () {
    $revealed = gateFlaggedArtwork();
    $other = gateFlaggedArtwork();

    $this->from(route('artwork.index'))
        ->post(route('mature-consent.store'), ['artwork_id' => $revealed->id])
        ->assertRedirect(route('artwork.index'));

    $props = gateProps($this->get(route('artwork.index')));

    expect(gateFind($props, $revealed->id))
        ->media_withheld->toBeFalse()
        ->media_urls->toBe($revealed->mediaUrlBuilder()->public());
    expect(gateFind($props, $other->id))->media_withheld->toBeTrue();
    assertNoMediaUrlsFor($props, $other);
});

test('revealing on the detail page reloads only the artwork prop, now with its image', function () {
    $flagged = gateFlaggedArtwork();
    $version = app(HandleInertiaRequests::class)->version(request());

    $response = $this->from(route('artwork.show', $flagged))
        ->followingRedirects()
        ->withHeaders([
            'X-Inertia' => 'true',
            'X-Inertia-Version' => (string) $version,
            'X-Inertia-Partial-Component' => 'Public/Artwork/Show',
            'X-Inertia-Partial-Data' => 'artwork',
        ])
        ->post(route('mature-consent.store'), ['artwork_id' => $flagged->id])
        ->assertOk();

    $props = $response->json('props');
    expect($props)->toHaveKey('artwork')->not->toHaveKey('seo')
        ->and($props['artwork']['media_withheld'])->toBeFalse()
        ->and($props['artwork']['media_urls'])->toBe($flagged->mediaUrlBuilder()->public());
});

test('show all exposes every flagged artwork and gallery cover for the session', function () {
    $first = gateFlaggedArtwork();
    $second = gateFlaggedArtwork();
    gateGalleryCoveredBy($second);

    $this->post(route('mature-consent.store'), ['all' => true])->assertRedirect();

    $props = gateProps($this->get(route('artwork.index')));
    expect(gateFind($props, $first->id)['media_urls'])->toBe($first->mediaUrlBuilder()->public());
    expect(gateFind($props, $second->id)['media_urls'])->toBe($second->mediaUrlBuilder()->public());

    $galleries = gateProps($this->get(route('art.index')));
    expect($galleries['galleries']['data'][0]['cover_urls'])->toBe($second->mediaUrlBuilder()->public());
});

test('the consent endpoint ignores missing, unpublished and unflagged artwork ids', function () {
    $draft = gateFlaggedArtwork(['publish_status' => 'draft']);
    $unflagged = gateArtwork();

    foreach ([999999, $draft->id, $unflagged->id] as $id) {
        $this->post(route('mature-consent.store'), ['artwork_id' => $id])->assertRedirect();
    }

    expect(session('mature_consent.ids', []))->toBe([]);
    expect(session('mature_consent.all', false))->toBeFalse();
});

test('the consent endpoint rejects a request with neither an artwork id nor show all', function () {
    $this->post(route('mature-consent.store'), [])->assertSessionHasErrors('artwork_id');
    $this->post(route('mature-consent.store'), ['artwork_id' => 'abc'])->assertSessionHasErrors('artwork_id');
});

test('the consent endpoint is a CSRF-protected, rate-limited web route', function () {
    $middleware = app('router')->getRoutes()->getByName('mature-consent.store')->gatherMiddleware();

    expect($middleware)->toContain('web')
        ->and(collect($middleware)->contains(fn ($m) => str_starts_with($m, 'throttle:')))->toBeTrue();

    $flagged = gateFlaggedArtwork();
    $statuses = collect(range(1, 31))->map(
        fn () => $this->post(route('mature-consent.store'), ['artwork_id' => $flagged->id])->status()
    );

    expect($statuses->last())->toBe(429);
});

test('seo for a flagged artwork, and for a gallery with a flagged cover, uses the site fallback image', function () {
    $safe = gateArtwork(['featured_flag' => true, 'created_on' => now()->subYear()]);
    $flagged = gateFlaggedArtwork(['featured_flag' => true, 'created_on' => now()]);
    $gallery = gateGalleryCoveredBy($flagged);
    $fallback = $safe->mediaUrlBuilder()->public()['display']['src'];

    // Consent must not change SEO output.
    $this->post(route('mature-consent.store'), ['all' => true]);

    $artworkSeo = gateProps($this->get(route('artwork.show', $flagged)))['seo'];
    expect($artworkSeo['og']['image'])->toBe($fallback)
        ->and($artworkSeo['twitter']['image'])->toBe($fallback)
        ->and($artworkSeo['jsonld']['image'])->toBe($fallback);
    assertNoMediaUrlsFor($artworkSeo, $flagged);

    $gallerySeo = gateProps($this->get(route('art.gallery', $gallery)))['seo'];
    expect($gallerySeo['og']['image'])->toBe($fallback);
    assertNoMediaUrlsFor($gallerySeo, $flagged);

    // The site-wide fallback itself never picks the newer flagged featured artwork.
    assertNoMediaUrlsFor(gateProps($this->get(route('home')))['seo'], $flagged);
});

test('the home hero never selects flagged artwork while the featured grid still lists it gated', function () {
    $safe = gateArtwork(['created_on' => now()->subYear()]);
    $flagged = gateFlaggedArtwork(['created_on' => now()]);

    $props = gateProps($this->get(route('home')));

    expect($props['heroArtwork']['id'])->toBe($safe->id);
    expect(collect($props['featuredArtworks'])->pluck('id')->all())->toBe([$flagged->id]);
    expect(gateFind($props['featuredArtworks'], $flagged->id))->media_withheld->toBeTrue();
});

test('unflagged artwork keeps its media urls without any consent', function () {
    $artwork = gateArtwork();
    $gallery = gateGalleryCoveredBy($artwork);
    $urls = $artwork->mediaUrlBuilder()->public();

    expect($urls)->not->toBe([]);

    foreach ([route('artwork.index'), route('artwork.show', $artwork), route('art.gallery', $gallery), route('home')] as $url) {
        expect(gateFind(gateProps($this->get($url)), $artwork->id))
            ->media_withheld->toBeFalse()
            ->media_urls->toBe($urls);
    }

    expect(gateProps($this->get(route('art.index')))['galleries']['data'][0]['cover_urls'])->toBe($urls);
    expect(gateProps($this->get(route('artwork.show', $artwork)))['seo']['og']['image'])->toBe($urls['display']['src']);
});
