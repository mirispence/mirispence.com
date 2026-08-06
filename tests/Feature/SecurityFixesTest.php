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
    $this->actingAs($admin)->get($url)->assertOk();

    // User with specific permission
    $privilegedUser = User::factory()->create();
    $privilegedUser->assignRole('admin');

    // Create permission and assign
    $permission = Permission::findOrCreate('can view source image');
    $privilegedUser->givePermissionTo($permission);

    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    // Auth and check
    $this->actingAs($privilegedUser);

    $this->get($url)->assertOk();
});
