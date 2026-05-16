<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestCatalogSeeder extends Seeder
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
