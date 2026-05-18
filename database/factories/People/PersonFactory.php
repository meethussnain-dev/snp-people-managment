<?php

namespace Database\Factories\People;

use App\Models\Language;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'language_id' => Language::factory(),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'sa_id_number' => fake()->unique()->numerify('#############'),
            'mobile_number' => fake()->numerify('08########'),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date(),
        ];
    }
}
