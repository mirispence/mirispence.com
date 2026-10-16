<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\MediaLibrary\HasMedia;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('admin can view books index', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

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
    $user->assignRole('admin');

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
    $user->assignRole('admin');
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
    $user->assignRole('admin');
    $book = Book::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.books.destroy', $book))
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseMissing('books', [
        'id' => $book->id,
    ]);
});

test('admin can create book pointing at a gallery', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.books.store'), [
            'title' => 'Gallery Book',
            'publish_status' => 'draft',
            'gallery_id' => $gallery->id,
        ])
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseHas('books', [
        'title' => 'Gallery Book',
        'gallery_id' => $gallery->id,
    ]);
});

test('admin can change and clear a book gallery', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $book = Book::factory()->create(['gallery_id' => Gallery::factory()->create()->id]);
    $other = Gallery::factory()->create();

    $payload = ['title' => $book->title, 'publish_status' => 'draft'];

    $this->actingAs($user)
        ->put(route('admin.books.update', $book), [...$payload, 'gallery_id' => $other->id])
        ->assertRedirect(route('admin.books.index'));
    expect($book->fresh()->gallery_id)->toBe($other->id);

    $this->actingAs($user)
        ->put(route('admin.books.update', $book), [...$payload, 'gallery_id' => null])
        ->assertRedirect(route('admin.books.index'));
    expect($book->fresh()->gallery_id)->toBeNull();
});

test('book gallery must exist', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->post(route('admin.books.store'), [
            'title' => 'Bad Gallery',
            'publish_status' => 'draft',
            'gallery_id' => 999999,
        ])
        ->assertSessionHasErrors('gallery_id');
});

test('deleting a gallery unlinks its books', function () {
    $gallery = Gallery::factory()->create();
    $book = Book::factory()->create(['gallery_id' => $gallery->id]);

    $gallery->delete();

    expect($book->fresh()->gallery_id)->toBeNull();
});

test('book no longer manages its own cover media', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    expect(Book::factory()->create())->not->toBeInstanceOf(HasMedia::class);

    $this->actingAs($user)
        ->post(route('admin.books.store'), [
            'title' => 'Upload Attempt',
            'publish_status' => 'draft',
            'cover' => UploadedFile::fake()->image('cover.jpg'),
        ])
        ->assertRedirect(route('admin.books.index'));

    $this->assertDatabaseMissing('media', ['model_type' => 'book']);
    $this->assertDatabaseMissing('media', ['model_type' => Book::class]);
});

test('book edit page offers galleries and omits media fields', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create(['name' => 'Cover Gallery']);
    $book = Book::factory()->create(['gallery_id' => $gallery->id]);

    $this->actingAs($user)
        ->get(route('admin.books.edit', $book))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Books/Edit')
            ->where('book.gallery_id', $gallery->id)
            ->missing('book.media')
            ->missing('book.media_urls')
            ->missing('book.thumb_url')
            ->missing('book.image_url')
            ->missing('book.gallery')
            ->has('galleries', 1)
            ->where('galleries.0.id', $gallery->id)
            ->where('galleries.0.name', 'Cover Gallery')
        );
});

test('books index rows show the gallery cover thumbnail', function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');

    $user = User::factory()->create();
    $user->assignRole('admin');
    $cover = Artwork::factory()->create();
    $cover->addMedia(UploadedFile::fake()->image('cover.jpg', 400, 600))->toMediaCollection('artwork');
    $gallery = Gallery::factory()->create();
    $gallery->artworks()->attach($cover->id);
    $gallery->update(['cover_artwork_id' => $cover->id]);
    $withGallery = Book::factory()->create(['gallery_id' => $gallery->id]);
    $without = Book::factory()->create();
    $thumb = $cover->fresh()->thumb_url;
    expect($thumb)->not->toBe('');

    $rows = collect($this->actingAs($user)
        ->get(route('admin.books.index'))
        ->assertOk()
        ->viewData('page')['props']['books']['data'])->keyBy('id');

    expect($rows[$withGallery->id]['cover_thumb_url'])->toBe($thumb)
        ->and($rows[$without->id]['cover_thumb_url'])->toBe('')
        ->and($rows[$withGallery->id])->not->toHaveKey('gallery');
});

test('book create page offers galleries', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    Gallery::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('admin.books.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Books/Create')
            ->has('galleries', 2)
        );
});
