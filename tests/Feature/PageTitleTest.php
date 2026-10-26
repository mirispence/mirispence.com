<?php

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

// The server owns the full public title (#48). Each entry builds the URL to
// visit, so route renames only touch this list.
dataset('public pages', [
    'home' => fn () => route('home'),
    'art (gallery list)' => fn () => route('art.index'),
    'gallery show' => fn () => route('art.gallery', Gallery::factory()->create(['publish_status' => 'published'])),
    'artwork index' => fn () => route('artwork.index'),
    'artwork show' => fn () => route('artwork.show', Artwork::factory()->create(['publish_status' => 'published'])),
    'books index' => fn () => route('books.index'),
    'book show' => fn () => route('books.show', Book::factory()->create(['publish_status' => 'published'])),
    'contact' => fn () => route('contact.create'),
]);

test('public page title contains the site name exactly once', function (Closure $url) {
    $seo = $this->get($url())->assertOk()->inertiaProps('seo');

    expect(substr_count($seo['title'], config('app.name')))->toBe(1);
})->with('public pages');

test('og and twitter titles match the document title', function (Closure $url) {
    $seo = $this->get($url())->assertOk()->inertiaProps('seo');

    expect($seo['og']['title'])->toBe($seo['title'])
        ->and($seo['twitter']['title'])->toBe($seo['title']);
})->with('public pages');

test('home title is exactly the site name', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('seo.title', config('app.name')));
});
