<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {--name= : The name of the user} {--email= : The email of the user} {--password= : The password of the user} {--role=admin : The role of the user (default: admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user from the CLI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password');
        $role = $this->option('role');

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists.");

            return 1;
        }

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
            ]);

            $this->info("User {$user->email} created successfully!");
        } catch (\Exception $e) {
            $this->error('Failed to create user: '.$e->getMessage());

            return 1;
        }

        return 0;
    }
}
