<?php

namespace Database\Factories;

use App\Models\CommissionRequest;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CommissionRequest>
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
            'contact_message_id' => ContactMessage::factory(),
            'project_description' => $this->faker->paragraph(),
            'budget_range' => '$100-$500',
            'desired_due_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['new', 'quoted', 'accepted', 'rejected', 'completed']),
            'quote_amount' => $this->faker->randomFloat(2, 100, 1000),
            'invoice_link' => $this->faker->url(),
        ];
    }
}
