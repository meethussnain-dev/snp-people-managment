<?php

namespace Database\Seeders\People;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Afrikaans', 'English', 'isiXhosa', 'isiZulu', 'Sesotho'] as $language) {
            Language::updateOrCreate(['name' => $language]);
        }
    }
}
