<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class   RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'can view source image',
            'can regenerate image thumbnails',
            'can upload art',
            'can upload book',
            'can edit art',
            'can edit book',
            'can see form answers',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign created permissions
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        Role::findOrCreate('user');

        // Assign admin role to initial user
        $initialAdminEmail = config('auth.initial_admin_email') ?? env('INITIAL_ADMIN_EMAIL');

        if ($initialAdminEmail) {
            $user = User::where('email', $initialAdminEmail)->first();
            if ($user) {
                $user->assignRole($adminRole);
            }
        } else {
            // Fallback: assign to the first user if no email is configured
            $firstUser = User::first();
            if ($firstUser) {
                $firstUser->assignRole($adminRole);
            }
        }
    }
}
