<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
});

test('store book validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->post(route('admin.books.store'), $data);

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
        ['title' => 'Test Book', 'publish_status' => 'invalid_status'],
        ['publish_status'],
    ],
    'non-boolean featured flag' => [
        ['title' => 'Test Book', 'publish_status' => 'draft', 'featured_flag' => 'not-a-bool'],
        ['featured_flag'],
    ],
    'invalid release date' => [
        ['title' => 'Test Book', 'publish_status' => 'draft', 'release_date' => 'not-a-date'],
        ['release_date'],
    ],
    'non-array external links or tags' => [
        ['title' => 'Test Book', 'publish_status' => 'draft', 'external_links' => 'not-an-array', 'tags' => 'not-an-array'],
        ['external_links', 'tags'],
    ],
]);

test('update book validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $book = Book::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('admin.books.update', $book), $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'missing title' => [
        ['publish_status' => 'draft'],
        ['title'],
    ],
    'invalid publish status' => [
        ['title' => 'Test Book', 'publish_status' => 'invalid_status'],
        ['publish_status'],
    ],
]);
