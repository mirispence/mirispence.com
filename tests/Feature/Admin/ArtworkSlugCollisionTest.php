<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('artwork slug is incremented on collision during store', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Artwork::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);

    $response = $this->actingAs($user)
        ->post(route('admin.artworks.store'), [
            'title' => 'Duplicate Title',
            'description' => 'Description',
            'publish_status' => 'draft',
            'nsfw_flag' => false,
            'featured_flag' => false,
        ]);

    $response->assertRedirect(route('admin.artworks.index'));

    $this->assertDatabaseHas('artworks', [
        'title' => 'Duplicate Title',
        'slug' => 'duplicate-title-1',
    ]);
});

test('artwork slug is incremented on collision during update', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Artwork::factory()->create(['title' => 'Target Title', 'slug' => 'target-title']);
    $artwork = Artwork::factory()->create(['title' => 'Original Title', 'slug' => 'original-title']);

    $response = $this->actingAs($user)
        ->put(route('admin.artworks.update', $artwork), [
            'title' => 'Target Title',
            'description' => 'Description',
            'publish_status' => 'draft',
            'nsfw_flag' => false,
            'featured_flag' => false,
        ]);

    $response->assertRedirect(route('admin.artworks.index'));

    $this->assertDatabaseHas('artworks', [
        'id' => $artwork->id,
        'title' => 'Target Title',
        'slug' => 'target-title-1',
    ]);
});

test('user is notified when slug is changed due to collision', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Artwork::factory()->create(['title' => 'Duplicate Title', 'slug' => 'duplicate-title']);

    $response = $this->actingAs($user)
        ->post(route('admin.artworks.store'), [
            'title' => 'Duplicate Title',
            'description' => 'Description',
            'publish_status' => 'draft',
            'nsfw_flag' => false,
            'featured_flag' => false,
        ]);

    $response->assertSessionHas('success', function ($message) {
        return str_contains($message, 'duplicate-title-1');
    });
});
