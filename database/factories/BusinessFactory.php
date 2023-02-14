<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->name();
        return [
            'alias' => Str::replace(' ', '-', Str::lower($name)),
            'name' => $name,
            'image_url' => fake()->imageUrl(),
            'is_closed' => false,
            'url' => fake()->url(),
            'review_count' => 0,
            'rating' => 0,
            'phone' => fake()->phoneNumber(),
            'display_phone' => fake()->phoneNumber(),
            'distance' => 0,
        ];
    }
}
