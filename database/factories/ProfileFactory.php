<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'description' => fake()->sentence(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'gender' => fake()->randomElement(['u', 'f', 'm']),
            'public' => fake()->randomElement([0, 1])

        ];
    }
}
