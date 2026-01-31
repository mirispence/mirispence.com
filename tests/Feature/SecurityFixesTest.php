<?php

use App\Models\Artwork;
use App\Models\User;
use App\Services\MarkdownRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles and permissions
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

    Storage::fake('media_private');
    Storage::fake('public');
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
    $this->actingAs($admin)->get($url)->assertOk();

    // User with specific permission
    $privilegedUser = User::factory()->create();

    // Create permission and assign
    $permission = Permission::findOrCreate('can view source image');
    $privilegedUser->givePermissionTo($permission);

    // Auth and check
    $this->actingAs($privilegedUser);

    // Debugging (private check)
    // dd($privilegedUser->hasPermissionTo('can view source image'));

    $this->get($url)->assertOk();
});

test('artwork model includes signed original url only for authorized users', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $artwork = Artwork::factory()->create();
    $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->toMediaCollection('artwork');

    // Guest
    expect($artwork->signed_urls['original'])->toBeNull();

    // User
    $this->actingAs($user);
    expect($artwork->fresh()->signed_urls['original'])->toBeNull();

    // Admin
    $this->actingAs($admin);
    expect($artwork->fresh()->signed_urls['original'])->not->toBeNull();
});
