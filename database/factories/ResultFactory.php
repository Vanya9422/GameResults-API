<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'member_id' => Member::factory(), // Создает связанного участника автоматически
            'milliseconds' => $this->faker->numberBetween(100, 100000), // Примерное время
        ];
    }
}
