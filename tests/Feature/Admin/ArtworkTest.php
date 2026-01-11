<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view artworks index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.artworks.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Artworks/Index')
            ->has('artworks.data', 0)
        );
});

test('admin can create artwork', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.artworks.store'), [
            'title' => 'New Artwork',
            'description' => 'Description',
            'publish_status' => 'draft',
            'nsfw_flag' => false,
            'featured_flag' => false,
        ])
        ->assertRedirect(route('admin.artworks.index'));

    $this->assertDatabaseHas('artworks', [
        'title' => 'New Artwork',
        'slug' => 'new-artwork',
    ]);
});

test('admin can update artwork', function () {
    $user = User::factory()->create();
    $artwork = Artwork::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.artworks.update', $artwork), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'publish_status' => 'published',
            'nsfw_flag' => true,
            'featured_flag' => true,
        ])
        ->assertRedirect(route('admin.artworks.index'));

    $this->assertDatabaseHas('artworks', [
        'id' => $artwork->id,
        'title' => 'Updated Title',
        'slug' => 'updated-title',
        'publish_status' => 'published',
        'nsfw_flag' => true,
        'featured_flag' => true,
    ]);
});

test('admin can delete artwork', function () {
    $user = User::factory()->create();
    $artwork = Artwork::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.artworks.destroy', $artwork))
        ->assertRedirect(route('admin.artworks.index'));

    $this->assertDatabaseMissing('artworks', [
        'id' => $artwork->id,
    ]);
});
