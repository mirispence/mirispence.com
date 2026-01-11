<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view chapters index', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    Chapter::factory()->create(['book_id' => $book->id]);

    $this->actingAs($user)
        ->get(route('admin.chapters.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Chapters/Index')
            ->has('chapters.data', 1)
        );
});

test('admin can create chapter', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.chapters.store'), [
            'book_id' => $book->id,
            'title' => 'New Chapter',
            'summary' => 'Summary',
            'body_markdown' => 'Content',
            'order' => 1,
            'is_sample' => false,
        ])
        ->assertRedirect(route('admin.chapters.index', ['book_id' => $book->id]));

    $this->assertDatabaseHas('chapters', [
        'title' => 'New Chapter',
        'slug' => 'new-chapter',
        'book_id' => $book->id,
    ]);
});

test('admin can update chapter', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $chapter = Chapter::factory()->create(['book_id' => $book->id]);

    $this->actingAs($user)
        ->put(route('admin.chapters.update', $chapter), [
            'book_id' => $book->id,
            'title' => 'Updated Chapter',
            'summary' => 'Updated Summary',
            'body_markdown' => 'Updated Content',
            'order' => 2,
            'is_sample' => true,
        ])
        ->assertRedirect(route('admin.chapters.index', ['book_id' => $book->id]));

    $this->assertDatabaseHas('chapters', [
        'id' => $chapter->id,
        'title' => 'Updated Chapter',
        'slug' => 'updated-chapter',
        'is_sample' => true,
    ]);
});

test('admin can delete chapter', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();
    $chapter = Chapter::factory()->create(['book_id' => $book->id]);

    $this->actingAs($user)
        ->delete(route('admin.chapters.destroy', $chapter))
        ->assertRedirect(route('admin.chapters.index', ['book_id' => $book->id]));

    $this->assertDatabaseMissing('chapters', [
        'id' => $chapter->id,
    ]);
});
