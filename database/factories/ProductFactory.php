<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = ['sport', 'bussiness', 'luxury'];
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'car_type' => fake()->randomElement($type),
            'image' => fake()->image(),
            'steering' => fake()->randomElement(['manual', 'auto']),
            'capacity' => fake()->randomElement(['2', '4']),
            'gasoline' => fake()->numberBetween(30, 200),
            'price' => fake()->randomNumber(),
            'rent_as' => fake()->randomElement(['day', 'weak', 'monthly']),
            'discount' => fake()->randomNumber()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
