<?php

use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

// #55: the detail page's metadata line links only published galleries and
// omits that part when there are none; an empty description renders nothing.

test('the artwork detail galleries prop is empty when the artwork is only in draft galleries', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published']);
    $artwork->galleries()->attach(Gallery::factory()->create(['publish_status' => 'draft']));

    $this->get(route('artwork.show', $artwork))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Artwork/Show')
            ->where('artwork.galleries', [])
        );
});

test('the artwork detail lists published galleries with the fields the metadata links need', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published']);
    $first = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Portraits']);
    $second = Gallery::factory()->create(['publish_status' => 'published', 'name' => 'Landscapes']);
    $draft = Gallery::factory()->create(['publish_status' => 'draft', 'name' => 'Unfinished']);
    $artwork->galleries()->attach([$first->id, $second->id, $draft->id]);

    $this->get(route('artwork.show', $artwork))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('artwork.galleries', 2)
            ->has('artwork.galleries.0', fn (Assert $gallery) => $gallery
                ->hasAll(['id', 'name', 'slug'])
                ->etc()
            )
            ->where('artwork.galleries', fn ($galleries) => collect($galleries)->pluck('name')->sort()->values()->all() === ['Landscapes', 'Portraits'])
        );
});

test('an artwork without a description sends an empty description_html', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published', 'description' => null]);

    $this->get(route('artwork.show', $artwork))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('artwork.description_html', ''));
});
