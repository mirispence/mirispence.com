<?php

use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

// ADR-0005: /art lists Galleries, /art/{gallery} shows one, /artwork lists every
// Artwork and /artwork/{artwork} is the Artwork's single canonical page.

test('the public art and artwork routes resolve to the ADR-0005 urls', function () {
    $gallery = Gallery::factory()->create(['name' => 'Portraits']);
    $artwork = Artwork::factory()->create(['title' => 'Blue Hour']);

    expect(route('art.index', [], false))->toBe('/art')
        ->and(route('art.gallery', $gallery, false))->toBe('/art/portraits')
        ->and(route('artwork.index', [], false))->toBe('/artwork')
        ->and(route('artwork.show', $artwork, false))->toBe('/artwork/blue-hour');
});

test('/art renders the gallery list', function () {
    Gallery::factory()->create(['publish_status' => 'published']);

    $this->get('/art')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Index')
            ->has('galleries.data', 1)
        );
});

test('/art/{gallery} renders the gallery and its published artworks', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
    $published = Artwork::factory()->create(['publish_status' => 'published']);
    $draft = Artwork::factory()->create(['publish_status' => 'draft']);
    $gallery->artworks()->attach([$published->id, $draft->id]);

    $this->get('/art/portraits')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Show')
            ->where('gallery.id', $gallery->id)
            ->has('artworks.data', 1)
            ->where('artworks.data.0.id', $published->id)
        );
});

test('/artwork renders every published artwork', function () {
    $published = Artwork::factory()->create(['publish_status' => 'published']);
    Artwork::factory()->create(['publish_status' => 'draft']);

    $this->get('/artwork')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Artwork/Index')
            ->has('artworks.data', 1)
            ->where('artworks.data.0.id', $published->id)
            ->where('activeTag', null)
            ->missing('galleries')
            ->where('seo.canonical', url('/artwork'))
            ->where('seo.description', fn ($d) => str_contains($d, 'Every artwork'))
        );
});

test('/artwork/{artwork} renders the artwork detail page', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Blue Hour']);

    $this->get('/artwork/blue-hour')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Artwork/Show')
            ->where('artwork.id', $artwork->id)
        );
});

test('an unpublished gallery or artwork returns 404 at the new urls', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'draft']);
    $artwork = Artwork::factory()->create(['publish_status' => 'draft']);

    $this->get(route('art.gallery', $gallery))->assertNotFound();
    $this->get(route('artwork.show', $artwork))->assertNotFound();
});

test('the old /galleries urls and /art/{artwork} detail urls return 404', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
    Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Blue Hour']);

    $this->get('/galleries')->assertNotFound();
    $this->get('/galleries/portraits')->assertNotFound();
    $this->get('/art/blue-hour')->assertNotFound();
});

test('the tag filter returns only tagged, published artwork', function () {
    $tag = Tag::factory()->create(['slug' => 'ink', 'name' => 'Ink']);
    $tagged = Artwork::factory()->create(['publish_status' => 'published']);
    $taggedDraft = Artwork::factory()->create(['publish_status' => 'draft']);
    Artwork::factory()->create(['publish_status' => 'published']);
    $tagged->tags()->attach($tag);
    $taggedDraft->tags()->attach($tag);

    $this->get(route('artwork.index', ['tag' => 'ink']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Artwork/Index')
            ->has('artworks.data', 1)
            ->where('artworks.data.0.id', $tagged->id)
            ->where('filters.tag', 'ink')
            ->where('activeTag', ['name' => 'Ink', 'slug' => 'ink'])
        );
});

test('an unknown tag shows an empty list, not an error', function () {
    Artwork::factory()->create(['publish_status' => 'published']);

    $this->get(route('artwork.index', ['tag' => 'no-such-tag']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('artworks.data', 0)
            ->where('filters.tag', 'no-such-tag')
            ->where('activeTag', null)
        );
});

test('the gallery filter is gone from /artwork', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
    $member = Artwork::factory()->create(['publish_status' => 'published']);
    Artwork::factory()->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($member->id);

    $this->get(route('artwork.index', ['gallery' => 'portraits']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('artworks.data', 2)
            ->missing('filters.gallery')
        );
});

test('the artwork detail page lists only the published galleries it belongs to', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published']);
    $published = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
    $draft = Gallery::factory()->create(['publish_status' => 'draft']);
    $artwork->galleries()->attach([$published->id, $draft->id]);

    $this->get(route('artwork.show', $artwork))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('artwork.galleries', 1)
            ->where('artwork.galleries.0', ['id' => $published->id, 'name' => 'Portraits', 'slug' => 'portraits'])
        );
});

test('artwork canonical and json-ld url use the artwork.show url', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Blue Hour']);

    $seo = $this->get(route('artwork.show', $artwork))->assertOk()->viewData('page')['props']['seo'];

    expect($seo['canonical'])->toBe(url('/artwork/blue-hour'))
        ->and($seo['jsonld']['url'])->toBe(url('/artwork/blue-hour'));
});

test('the sitemap contains the new urls and none of the old ones', function () {
    $path = public_path('sitemap.xml');
    $backup = File::exists($path) ? File::get($path) : null;

    try {
        $artwork = Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Blue Hour']);
        $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
        Artwork::factory()->create(['publish_status' => 'draft', 'title' => 'Secret Sketch']);

        $this->artisan('sitemap:generate')->assertSuccessful();

        preg_match_all('#<loc>([^<]+)</loc>#', File::get($path), $matches);
        $paths = collect($matches[1])->map(fn ($loc) => parse_url($loc, PHP_URL_PATH) ?? '/')->all();

        expect($paths)->toContain('/art', '/art/portraits', '/artwork', '/artwork/blue-hour')
            ->not->toContain('/galleries', '/galleries/portraits', '/art/blue-hour', '/artwork/secret-sketch');
        expect(collect($paths)->filter(fn ($p) => str_starts_with($p, '/galleries')))->toBeEmpty();
    } finally {
        $backup === null ? File::delete($path) : File::put($path, $backup);
    }
});
