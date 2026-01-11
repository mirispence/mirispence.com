<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view books index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.books.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Books/Index')
            ->has('books.data', 0)
        );
});

test('admin can create book', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.books.store'), [
            'title' => 'New Book',
            'description' => 'Description',
            'publish_status' => 'draft',
            'featured_flag' => false,
        ])
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseHas('books', [
        'title' => 'New Book',
        'slug' => 'new-book',
    ]);
});

test('admin can update book', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.books.update', $book), [
            'title' => 'Updated Book',
            'description' => 'Updated Description',
            'publish_status' => 'published',
            'featured_flag' => true,
        ])
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'Updated Book',
        'slug' => 'updated-book',
        'publish_status' => 'published',
        'featured_flag' => true,
    ]);
});

test('admin can delete book', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.books.destroy', $book))
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseMissing('books', [
        'id' => $book->id,
    ]);
});
