<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Book::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'summary' => $this->faker->paragraph(),
            'body_markdown' => $this->faker->paragraphs(3, true),
            'order' => $this->faker->numberBetween(1, 20),
            'is_sample' => $this->faker->boolean(10),
        ];
    }
}
