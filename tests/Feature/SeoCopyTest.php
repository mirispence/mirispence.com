<?php

use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Meta descriptions live as named constants in SeoBuilder (#57). These literals
// pin the rendered text so moving the copy can't change it.
dataset('static page descriptions', [
    'home' => [fn () => route('home'), 'Portfolio of Miri Spence - Concept Art, Illustration, and Fantasy Books.'],
    'art (gallery list)' => [fn () => route('art.index'), 'Browse the art gallery of Miri Spence. Digital paintings, character designs, and illustrations.'],
    'artwork index' => [fn () => route('artwork.index'), 'Every artwork by Miri Spence. Digital paintings, character designs, and illustrations.'],
    'books index' => [fn () => route('books.index'), 'Explore the books and stories by Miri Spence. Fantasy novels, world-building, and more.'],
    'contact' => [fn () => route('contact.create'), 'Get in touch with Miri Spence for commissions, inquiries, or just to say hello.'],
]);

test('static public pages keep their meta description', function (Closure $url, string $description) {
    expect($this->get($url())->assertOk()->inertiaProps('seo')['description'])->toBe($description);
})->with('static page descriptions');

test('artwork page title ends in "- Artwork"', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Moon Tide']);

    $seo = $this->get(route('artwork.show', $artwork))->assertOk()->inertiaProps('seo');

    expect($seo['title'])->toBe('Moon Tide - Artwork - '.config('app.name'));
});

test('gallery page title still ends in "- Art"', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Sketches']);

    $seo = $this->get(route('art.gallery', $gallery))->assertOk()->inertiaProps('seo');

    expect($seo['title'])->toBe('Sketches - Art - '.config('app.name'));
});

test('artwork and gallery without a description fall back to the templated text', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'title' => 'Moon Tide', 'description' => null]);
    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Sketches', 'description' => null]);

    expect($this->get(route('artwork.show', $artwork))->inertiaProps('seo')['description'])
        ->toBe("View 'Moon Tide' by Miri Spence.")
        ->and($this->get(route('art.gallery', $gallery))->inertiaProps('seo')['description'])
        ->toBe('View the Sketches gallery by Miri Spence.');
});
