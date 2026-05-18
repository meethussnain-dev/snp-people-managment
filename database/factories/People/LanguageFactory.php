<?php

namespace Database\Factories\People;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Afrikaans', 'English', 'isiXhosa', 'isiZulu', 'Sesotho']),
        ];
    }
}
