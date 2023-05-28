<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\LoanStatuses;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'amount' => fake()->numberBetween(1000, 5000),
            'term_duration' => fake()->numberBetween(30, 90),
            'status_id' => LoanStatuses::PENDING,
        ];
    }
}
