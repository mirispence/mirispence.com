<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
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

test('admin can view galleries index', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

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
    $user->assignRole('admin');

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
    $user->assignRole('admin');
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
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.galleries.destroy', $gallery))
        ->assertRedirect(route('admin.galleries.index'));

    $this->assertDatabaseMissing('galleries', [
        'id' => $gallery->id,
    ]);
});

test('admin can set a gallery cover to one of its member artworks', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();
    $artwork = Artwork::factory()->create();
    $gallery->artworks()->attach($artwork->id, ['sort_order' => 5]);

    $this->actingAs($user)
        ->put(route('admin.galleries.update', $gallery), [
            'name' => $gallery->name,
            'publish_status' => 'published',
            'cover_artwork_id' => $artwork->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.galleries.index'));

    expect($gallery->fresh()->coverArtwork->is($artwork))->toBeTrue();
    expect($gallery->artworks()->first()->pivot->sort_order)->toBe(5);
});

test('admin cannot set a gallery cover to an artwork outside the gallery', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();
    $otherGallery = Gallery::factory()->create();
    $outsider = Artwork::factory()->create();
    $otherGallery->artworks()->attach($outsider->id);

    $this->actingAs($user)
        ->put(route('admin.galleries.update', $gallery), [
            'name' => $gallery->name,
            'publish_status' => 'published',
            'cover_artwork_id' => $outsider->id,
        ])
        ->assertSessionHasErrors('cover_artwork_id');

    expect($gallery->fresh()->cover_artwork_id)->toBeNull();
});

test('admin can clear a gallery cover', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();
    $artwork = Artwork::factory()->create();
    $gallery->artworks()->attach($artwork->id);
    $gallery->update(['cover_artwork_id' => $artwork->id]);

    $this->actingAs($user)
        ->put(route('admin.galleries.update', $gallery), [
            'name' => $gallery->name,
            'publish_status' => 'published',
            'cover_artwork_id' => null,
        ])
        ->assertSessionHasNoErrors();

    expect($gallery->fresh()->cover_artwork_id)->toBeNull();
});

test('creating a gallery with a cover artwork is rejected because it has no members yet', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $artwork = Artwork::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.galleries.store'), [
            'name' => 'New Gallery',
            'publish_status' => 'draft',
            'cover_artwork_id' => $artwork->id,
        ])
        ->assertSessionHasErrors('cover_artwork_id');

    $this->assertDatabaseMissing('galleries', ['name' => 'New Gallery']);
});

test('deleting the cover artwork clears the gallery cover', function () {
    $gallery = Gallery::factory()->create();
    $artwork = Artwork::factory()->create();
    $gallery->artworks()->attach($artwork->id);
    $gallery->update(['cover_artwork_id' => $artwork->id]);

    $artwork->delete();

    expect($gallery->fresh()->cover_artwork_id)->toBeNull();
});

test('removing the cover artwork from a gallery via artwork update clears only that gallery cover', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $artwork = Artwork::factory()->create();
    $leaving = Gallery::factory()->create();
    $staying = Gallery::factory()->create();
    $artwork->galleries()->attach([$leaving->id, $staying->id]);
    $leaving->update(['cover_artwork_id' => $artwork->id]);
    $staying->update(['cover_artwork_id' => $artwork->id]);

    $this->actingAs($user)
        ->put(route('admin.artworks.update', $artwork), [
            'title' => $artwork->title,
            'publish_status' => 'published',
            'galleries' => [$staying->id],
        ])
        ->assertSessionHasNoErrors();

    expect($leaving->fresh()->cover_artwork_id)->toBeNull()
        ->and($staying->fresh()->cover_artwork_id)->toBe($artwork->id);
});

test('removing a non-cover artwork from a gallery keeps the gallery cover', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $cover = Artwork::factory()->create();
    $other = Artwork::factory()->create();
    $gallery = Gallery::factory()->create();
    $gallery->artworks()->attach([$cover->id, $other->id]);
    $gallery->update(['cover_artwork_id' => $cover->id]);

    $this->actingAs($user)
        ->put(route('admin.artworks.update', $other), [
            'title' => $other->title,
            'publish_status' => 'published',
            'galleries' => [],
        ])
        ->assertSessionHasNoErrors();

    expect($gallery->fresh()->cover_artwork_id)->toBe($cover->id);
});

test('gallery cover urls ignore a cover artwork that is no longer a member', function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');

    $cover = Artwork::factory()->create(['publish_status' => 'published']);
    $cover->addMedia(UploadedFile::fake()->image('cover.jpg'))->toMediaCollection('artwork');
    $gallery = Gallery::factory()->create(['publish_status' => 'published']);
    $gallery->artworks()->attach($cover->id);
    $gallery->update(['cover_artwork_id' => $cover->id]);

    expect($gallery->fresh()->cover_urls)->toHaveKey('thumb');

    // Membership removed outside the admin artwork flow, leaving a stale pointer.
    $gallery->artworks()->detach($cover->id);

    expect($gallery->fresh()->cover_urls)->toBe([]);
});

test('edit page offers only the gallery own artworks as cover candidates', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();
    $member = Artwork::factory()->create(['title' => 'Member']);
    Artwork::factory()->create(['title' => 'Outsider']);
    $gallery->artworks()->attach($member->id);

    $this->actingAs($user)
        ->get(route('admin.galleries.edit', $gallery))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Galleries/Edit')
            ->has('coverCandidates', 1)
            ->where('coverCandidates.0.id', $member->id)
            ->where('coverCandidates.0.title', 'Member')
            ->missing('gallery.media_urls')
            ->missing('gallery.thumb_url')
            ->missing('gallery.image_url')
            ->missing('gallery.media')
        );
});

test('gallery no longer has its own media collection', function () {
    expect(new Gallery)->not->toBeInstanceOf(HasMedia::class);
    expect(Gallery::factory()->create()->toArray())
        ->not->toHaveKeys(['media_urls', 'thumb_url', 'image_url', 'media']);
});

test('the old cover upload field is ignored', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $gallery = Gallery::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.galleries.update', $gallery), [
            'name' => $gallery->name,
            'publish_status' => 'published',
            'cover' => UploadedFile::fake()->image('cover.jpg'),
        ])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseMissing('media', ['model_type' => Gallery::class]);
});
