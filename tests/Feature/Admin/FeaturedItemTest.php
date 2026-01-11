<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\FeaturedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view featured items index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.featured-items.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/FeaturedItems/Index')
            ->has('featuredItems.data', 0)
        );
});

test('admin can create featured item', function () {
    $user = User::factory()->create();
    $artwork = Artwork::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.featured-items.store'), [
            'item_type' => 'artwork',
            'item_id' => $artwork->id,
            'display_context' => 'home',
            'display_order' => 1,
        ])
        ->assertRedirect(route('admin.featured-items.index'));

    $this->assertDatabaseHas('featured_items', [
        'item_type' => 'artwork',
        'item_id' => $artwork->id,
        'display_context' => 'home',
        'display_order' => 1,
    ]);
});

test('admin can update featured item', function () {
    $user = User::factory()->create();
    $artwork = Artwork::factory()->create();
    $featuredItem = FeaturedItem::create([
        'item_type' => 'artwork',
        'item_id' => $artwork->id,
        'display_context' => 'home',
        'display_order' => 1,
    ]);

    $this->actingAs($user)
        ->put(route('admin.featured-items.update', $featuredItem), [
            'item_type' => 'artwork',
            'item_id' => $artwork->id,
            'display_context' => 'sidebar',
            'display_order' => 2,
        ])
        ->assertRedirect(route('admin.featured-items.index'));

    $this->assertDatabaseHas('featured_items', [
        'id' => $featuredItem->id,
        'display_context' => 'sidebar',
        'display_order' => 2,
    ]);
});

test('admin can delete featured item', function () {
    $user = User::factory()->create();
    $artwork = Artwork::factory()->create();
    $featuredItem = FeaturedItem::create([
        'item_type' => 'artwork',
        'item_id' => $artwork->id,
        'display_context' => 'home',
        'display_order' => 1,
    ]);

    $this->actingAs($user)
        ->delete(route('admin.featured-items.destroy', $featuredItem))
        ->assertRedirect(route('admin.featured-items.index'));

    $this->assertDatabaseMissing('featured_items', [
        'id' => $featuredItem->id,
    ]);
});
