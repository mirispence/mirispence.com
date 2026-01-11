<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artwork>
 */
class ArtworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'alt_text' => $this->faker->sentence(),
            'created_on' => $this->faker->date(),
            'publish_status' => $this->faker->randomElement(['draft', 'published']),
            'nsfw_flag' => $this->faker->boolean(10),
            'featured_flag' => $this->faker->boolean(20),
            'metadata' => ['technique' => $this->faker->word()],
        ];
    }
}
