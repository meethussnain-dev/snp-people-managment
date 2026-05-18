<?php

namespace Database\Factories\People;

use App\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterestFactory extends Factory
{
    protected $model = Interest::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Art', 'Cooking', 'Gaming', 'Music', 'Reading', 'Sports', 'Technology', 'Travel']),
        ];
    }
}
