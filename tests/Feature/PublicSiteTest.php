<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('art index page renders with pagination when more than 6 artworks exist', function () {
    Artwork::factory()->count(7)->create(['publish_status' => 'published']);

    $this->get(route('art.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Art/Index')
            ->has('artworks.data', 6)
            ->has('artworks.links') // Root links object (first, last, etc)
            ->has('artworks.meta') // Meta object containing links array
            ->where('artworks.meta.total', 7)
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

test('galleries index page renders with pagination', function () {
    Gallery::factory()->count(7)->create(['publish_status' => 'published']);

    $this->get(route('galleries.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Index')
            ->has('galleries.data', 6)
            ->has('galleries.links') // Array of links for raw paginator
            // Raw paginator does not have 'meta' wrapper by default
        );
});

test('galleries index renders each gallery chosen cover from public media urls only', function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');

    $cover = Artwork::factory()->create(['publish_status' => 'published']);
    $cover->addMedia(UploadedFile::fake()->image('cover.jpg', 400, 300))->toMediaCollection('artwork');
    $originalUrl = $cover->getFirstMedia('artwork')->getUrl();

    $gallery = Gallery::factory()->create(['publish_status' => 'published', 'sort_order' => 1]);
    $gallery->artworks()->attach($cover->id);
    $gallery->update(['cover_artwork_id' => $cover->id]);

    $response = $this->get(route('galleries.index'))->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Public/Galleries/Index')
        ->where('galleries.data.0.cover_urls', $cover->fresh()->mediaUrlBuilder()->public())
        ->has('galleries.data.0.cover_urls.grid.src')
        ->missing('galleries.data.0.cover_urls.original')
        ->missing('galleries.data.0.cover_artwork')
    );

    $galleries = json_encode($response->viewData('page')['props']['galleries']);
    expect($galleries)->not->toContain('"original"')
        ->and($galleries)->not->toContain(json_encode($originalUrl));
});

test('galleries index hides a cover artwork that is not published', function () {
    $draft = Artwork::factory()->create(['publish_status' => 'draft']);
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($draft->id);
    $gallery->update(['cover_artwork_id' => $draft->id]);

    $this->get(route('galleries.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('galleries.data.0.cover_urls', [])
        );
});

test('galleries show page renders gallery with paginated artworks prop', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);

    $this->get(route('galleries.show', $gallery))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Show')
            ->where('gallery.id', $gallery->id)
            ->has('artworks.data')
            ->has('artworks.links')
            ->has('artworks.meta')
        );
});

test('galleries show page paginates artworks at 6 per page', function () {
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $artworks = Artwork::factory()->count(8)->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($artworks->pluck('id'));

    $this->get(route('galleries.show', $gallery))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Galleries/Show')
            ->has('artworks.data', 6)
            ->where('artworks.meta.total', 8)
            ->where('artworks.meta.per_page', 6)
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
