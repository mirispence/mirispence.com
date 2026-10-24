<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admin can create user', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user = User::where('email', 'new@example.com')->firstOrFail();
    expect($user->name)->toBe('New User')
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue();
});

test('admin can update user without changing password', function () {
    $user = User::factory()->create(['password' => Hash::make('original-pass')]);

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $user), [
            'name' => 'Renamed',
            'email' => 'renamed@example.com',
            'password' => '',
            'password_confirmation' => '',
            'role' => 'admin',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->name)->toBe('Renamed')
        ->and($user->email)->toBe('renamed@example.com')
        ->and(Hash::check('original-pass', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue();
});

test('admin can update user password', function () {
    $user = User::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'role' => 'admin',
        ])
        ->assertRedirect(route('admin.users.index'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});
