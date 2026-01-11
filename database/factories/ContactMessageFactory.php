<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'subject' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['general', 'commission']),
            'status' => $this->faker->randomElement(['new', 'read', 'replied', 'archived']),
            'metadata' => ['ip' => $this->faker->ipv4()],
        ];
    }
}
