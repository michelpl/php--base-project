<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charge>
 */
class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'government_id' => fake()->unique()->numberBetween(10000000000, 99999999999),
            'email' => fake()->unique()->email(),
            'debt_amount' => fake()->numberBetween(100, 9999),
            'debt_due_date' => fake()->dateTimeBetween("now", "+1 year"),
            'debt_id' => fake()->unique()->numberBetween(1000, 9999)
        ];
    }
}
