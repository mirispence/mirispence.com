<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
});

test('store chapter validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->post(route('admin.chapters.store'), $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'missing fields' => [
        ['summary' => 'Some summary'],
        ['book_id', 'title', 'body_markdown', 'order'],
    ],
    'invalid book_id' => [
        ['book_id' => 999, 'title' => 'Ch 1', 'body_markdown' => 'Body', 'order' => 1],
        ['book_id'],
    ],
    'title too long' => [
        ['title' => str_repeat('a', 256), 'body_markdown' => 'Body', 'order' => 1],
        ['title'],
    ],
    'non-integer order' => [
        ['title' => 'Ch 1', 'body_markdown' => 'Body', 'order' => 'not-an-int'],
        ['order'],
    ],
    'non-boolean is_sample' => [
        ['title' => 'Ch 1', 'body_markdown' => 'Body', 'order' => 1, 'is_sample' => 'not-a-bool'],
        ['is_sample'],
    ],
]);

test('update chapter validation fails with invalid data', function (array $data, array $errors) {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $book = Book::factory()->create();
    $chapter = Chapter::factory()->create(['book_id' => $book->id]);

    $response = $this->actingAs($user)
        ->put(route('admin.chapters.update', $chapter), $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'missing fields' => [
        ['summary' => 'Some summary'],
        ['book_id', 'title', 'body_markdown', 'order'],
    ],
]);
