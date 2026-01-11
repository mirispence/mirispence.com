<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view tags index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.tags.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Tags/Index')
            ->has('tags.data', 0)
        );
});

test('admin can create tag', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.tags.store'), [
            'name' => 'New Tag',
            'type' => 'artwork',
        ])
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseHas('tags', [
        'name' => 'New Tag',
        'slug' => 'new-tag',
        'type' => 'artwork',
    ]);
});

test('admin can update tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.tags.update', $tag), [
            'name' => 'Updated Tag',
            'type' => 'book',
        ])
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => 'Updated Tag',
        'slug' => 'updated-tag',
        'type' => 'book',
    ]);
});

test('admin can delete tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.tags.destroy', $tag))
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});
