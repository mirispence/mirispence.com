<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view galleries index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.galleries.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Galleries/Index')
            ->has('galleries.data', 0)
        );
});

test('admin can create gallery', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.galleries.store'), [
            'name' => 'New Gallery',
            'description' => 'Description',
            'publish_status' => 'draft',
            'sort_order' => 1,
        ])
        ->assertRedirect(route('admin.galleries.index'));

    $this->assertDatabaseHas('galleries', [
        'name' => 'New Gallery',
        'slug' => 'new-gallery',
    ]);
});

test('admin can update gallery', function () {
    $user = User::factory()->create();
    $gallery = Gallery::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.galleries.update', $gallery), [
            'name' => 'Updated Gallery',
            'description' => 'Updated Description',
            'publish_status' => 'published',
            'sort_order' => 2,
        ])
        ->assertRedirect(route('admin.galleries.index'));

    $this->assertDatabaseHas('galleries', [
        'id' => $gallery->id,
        'name' => 'Updated Gallery',
        'slug' => 'updated-gallery',
        'publish_status' => 'published',
        'sort_order' => 2,
    ]);
});

test('admin can delete gallery', function () {
    $user = User::factory()->create();
    $gallery = Gallery::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.galleries.destroy', $gallery))
        ->assertRedirect(route('admin.galleries.index'));

    $this->assertDatabaseMissing('galleries', [
        'id' => $gallery->id,
    ]);
});
