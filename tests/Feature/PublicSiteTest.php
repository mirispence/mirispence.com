<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('home page renders with featured items', function () {
    Artwork::factory()->create(['featured_flag' => true, 'publish_status' => 'published']);
    Book::factory()->create(['featured_flag' => true, 'publish_status' => 'published']);

    $this->get(route('home'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Home')
            ->has('featuredArtworks', 1)
            ->has('featuredBooks', 1)
        );
});

test('art index page renders with artworks', function () {
    Artwork::factory()->count(3)->create(['publish_status' => 'published']);

    $this->get(route('art.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Art/Index')
            ->has('artworks.data', 3)
            ->has('galleries')
        );
});

test('art show page renders artwork', function () {
    $artwork = Artwork::factory()->create(['publish_status' => 'published']);

    $this->get(route('art.show', $artwork))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Art/Show')
            ->where('artwork.id', $artwork->id)
        );
});

test('galleries index page renders', function () {
    Gallery::factory()->count(2)->create(['publish_status' => 'published']);

    $this->get(route('galleries.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Index')
            ->has('galleries', 2)
        );
});

test('galleries show page renders gallery', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);

    $this->get(route('galleries.show', $gallery))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Show')
            ->where('gallery.id', $gallery->id)
        );
});

test('books index page renders', function () {
    Book::factory()->count(2)->create(['publish_status' => 'published']);

    $this->get(route('books.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Books/Index')
            ->has('books', 2)
        );
});

test('books show page renders book', function () {
    $book = Book::factory()->create(['publish_status' => 'published']);

    $this->get(route('books.show', $book))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Books/Show')
            ->where('book.id', $book->id)
        );
});

test('contact page renders', function () {
    $this->get(route('contact.create'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Contact')
        );
});

test('contact form submits successfully', function () {
    $this->post(route('contact.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
        'type' => 'general',
    ])
    ->assertRedirect()
    ->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'test@example.com',
    ]);
});
