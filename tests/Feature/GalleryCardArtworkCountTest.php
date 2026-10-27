<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('art index gallery cards count only published artworks', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $published = Artwork::factory()->count(2)->create(['publish_status' => 'published']);
    $unpublished = Artwork::factory()->create(['publish_status' => 'draft']);
    $gallery->artworks()->attach([...$published->pluck('id'), $unpublished->id]);

    $empty = Gallery::factory()->create(['publish_status' => 'published', 'sort_order' => $gallery->sort_order + 1]);
    $empty->artworks()->attach(Artwork::factory()->create(['publish_status' => 'draft'])->id);

    $this->get(route('art.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Index')
            ->where('galleries.data.0.id', $gallery->id)
            ->where('galleries.data.0.published_artworks_count', 2)
            ->where('galleries.data.1.id', $empty->id)
            ->where('galleries.data.1.published_artworks_count', 0)
        );
});

test('art index sends pagination links where the page reads them', function () {
    Gallery::factory()->count(7)->create(['publish_status' => 'published']);

    $this->get(route('art.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Index')
            ->has('galleries.data', 6)
            ->has('galleries.meta.links')
        );
});
