<?php

namespace Database\Seeders;

use Database\Seeders\Auth\AdminAccountSeeder;
use Database\Seeders\People\InterestSeeder;
use Database\Seeders\People\LanguageSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminAccountSeeder::class,
            LanguageSeeder::class,
            InterestSeeder::class,
        ]);
    }
}
