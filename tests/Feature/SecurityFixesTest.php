<?php

use App\Models\Artwork;
use App\Models\User;
use App\Services\MarkdownRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles and permissions
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');
});

test('markdown renderer strips unsafe html', function () {
    $renderer = app(MarkdownRenderer::class);

    $markdown = "Hello <script>alert('xss')</script> **Bold**";
    $html = $renderer->toHtml($markdown);

    expect($html)->not->toContain('<script>')
        ->toContain('<strong>Bold</strong>');
});

test('admin routes are protected by admin role', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
});

test('original media access requires permission', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $artwork = Artwork::factory()->create();
    $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->toMediaCollection('artwork');

    $media = $artwork->getFirstMedia('artwork');
    $url = route('admin.media.original', $media->id);

    // Guest
    $this->get($url)->assertRedirect(route('login'));

    // Regular user (no permission)
    $this->actingAs($user)->get($url)->assertForbidden();

    // Admin (has all permissions)
    $this->actingAs($admin)->get($url)->assertRedirect();

    // User with specific permission
    $privilegedUser = User::factory()->create();
    $privilegedUser->assignRole('admin');

    // Create permission and assign
    $permission = Permission::findOrCreate('can view source image');
    $privilegedUser->givePermissionTo($permission);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    // Auth and check
    $this->actingAs($privilegedUser);

    $this->get($url)->assertRedirect();
});

test('original media redirects to a signed temporary url on a non-local (s3-like) disk', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $artwork = Artwork::factory()->create();
    $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->toMediaCollection('artwork');

    $media = $artwork->getFirstMedia('artwork');

    // Swap the faked local disk for a real S3-driver disk (as R2 is in production).
    // Presigning happens locally, so no network access is needed; getPath() on this
    // disk does not resolve to a local file, which is what broke the old controller.
    config()->set('filesystems.disks.r2_private', [
        'driver' => 's3',
        'key' => 'test-key',
        'secret' => 'test-secret',
        'region' => 'us-east-1',
        'bucket' => 'private-bucket',
        'endpoint' => 'https://account.r2.cloudflarestorage.com',
        'use_path_style_endpoint' => true,
    ]);
    config()->set('media-library.temporary_url_default_lifetime', 7);
    Storage::forgetDisk('r2_private');

    $response = $this->actingAs($admin)->get(route('admin.media.original', $media->id));

    $response->assertRedirect();
    $location = $response->headers->get('Location');

    expect($location)
        ->toStartWith('https://account.r2.cloudflarestorage.com/private-bucket/')
        ->toContain($media->file_name)
        ->toContain('X-Amz-Signature=')
        ->toContain('X-Amz-Expires=420');
});

test('session data is stored using json serialization, not php serialization', function () {
    $store = app('session.store');
    $store->put('foo', 'bar');
    $store->save();

    $raw = $store->getHandler()->read($store->getId());

    expect(json_decode($raw, true))->toBeArray()->toHaveKey('foo', 'bar')
        ->and($raw)->not->toContain('s:3:"foo"');
});
