<?php

namespace Tests\Feature\Admin;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
});

test('store artwork validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->post(route('admin.artworks.store'), $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'missing title' => [
        ['publish_status' => 'draft'],
        ['title'],
    ],
    'title too long' => [
        ['title' => str_repeat('a', 256), 'publish_status' => 'draft'],
        ['title'],
    ],
    'invalid publish status' => [
        ['title' => 'Test Art', 'publish_status' => 'invalid_status'],
        ['publish_status'],
    ],
    'non-boolean flags' => [
        ['title' => 'Test Art', 'publish_status' => 'draft', 'nsfw_flag' => 'not-a-bool', 'featured_flag' => 'not-a-bool'],
        ['nsfw_flag', 'featured_flag'],
    ],
    'non-array relationships' => [
        ['title' => 'Test Art', 'publish_status' => 'draft', 'galleries' => 'not-an-array', 'tags' => 'not-an-array'],
        ['galleries', 'tags'],
    ],
]);

test('update artwork validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $artwork = Artwork::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('admin.artworks.update', $artwork), $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'missing title' => [
        ['publish_status' => 'draft'],
        ['title'],
    ],
    'title too long' => [
        ['title' => str_repeat('a', 256), 'publish_status' => 'draft'],
        ['title'],
    ],
    'invalid publish status' => [
        ['title' => 'Test Art', 'publish_status' => 'invalid_status'],
        ['publish_status'],
    ],
]);
