<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin can view messages index', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.messages.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Messages/Index')
            ->has('messages.data', 0)
        );
});

test('admin can view message details', function () {
    $user = User::factory()->create();
    $message = ContactMessage::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.messages.show', $message))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Messages/Show')
            ->where('message.id', $message->id)
        );
});

test('admin can update message status', function () {
    $user = User::factory()->create();
    $message = ContactMessage::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.messages.index'))
        ->put(route('admin.messages.update', $message), [
            'status' => 'read',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.messages.index'));

    $this->assertDatabaseHas('contact_messages', [
        'id' => $message->id,
        'status' => 'read',
    ]);
});

test('admin can delete message', function () {
    $user = User::factory()->create();
    $message = ContactMessage::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.messages.destroy', $message))
        ->assertRedirect(route('admin.messages.index'));

    $this->assertDatabaseMissing('contact_messages', [
        'id' => $message->id,
    ]);
});
