<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call(
            [
                CountriesSeeder::class,
                ShowAudienceSeeder::class,
                LanguagesSeeder::class,
                PersonalitySeeder::class,
                ProductionTypeSeeder::class,
                ShowTypeSeeder::class,
                EventTypeSeeder::class,
                CollectiveCategoriesSeeder::class,
                ContinentsCountriesSeeder::class,
                VenueCategoriesSeeder::class,
            ]
        );
    }
}
