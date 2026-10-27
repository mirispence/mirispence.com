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
            // Deterministic: flagged artwork has its media withheld on public pages
            // (ADR-0004), so a random flag would make public-page tests flaky.
            'nsfw_flag' => false,
            'featured_flag' => $this->faker->boolean(20),
            'metadata' => ['technique' => $this->faker->word()],
        ];
    }

    /**
     * A mature (nsfw_flag) artwork.
     */
    public function mature(): static
    {
        return $this->state(fn () => ['nsfw_flag' => true]);
    }
}
