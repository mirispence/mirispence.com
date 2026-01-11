<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'publish_status' => $this->faker->randomElement(['draft', 'published']),
            'featured_flag' => $this->faker->boolean(20),
            'release_date' => $this->faker->date(),
            'external_links' => ['amazon' => $this->faker->url()],
        ];
    }
}
