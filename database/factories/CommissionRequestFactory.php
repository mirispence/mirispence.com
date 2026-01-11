<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommissionRequest>
 */
class CommissionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_message_id' => \App\Models\ContactMessage::factory(),
            'project_description' => $this->faker->paragraph(),
            'budget_range' => '$100-$500',
            'desired_due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['new', 'quoted', 'accepted', 'rejected', 'completed']),
            'quote_amount' => $this->faker->randomFloat(2, 100, 1000),
            'invoice_link' => $this->faker->url(),
        ];
    }
}
