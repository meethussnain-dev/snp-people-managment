<?php

namespace Database\Seeders\People;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Art', 'Cooking', 'Gaming', 'Music', 'Reading', 'Sports', 'Technology', 'Travel'] as $interest) {
            Interest::updateOrCreate(['name' => $interest]);
        }
    }
}
