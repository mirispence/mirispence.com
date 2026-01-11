<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeaturedItem>
 */
class FeaturedItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_type' => $this->faker->randomElement(['artwork', 'book']),
            'item_id' => $this->faker->randomDigitNotNull(),
            'display_context' => 'home',
            'display_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
