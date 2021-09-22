<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ContinentsCountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $continents = [
            ['name' => 'Worldwide', 'code' => null],
            ['name' => 'North America', 'code' => null],
            ['name' => 'South America', 'code' => null],
            ['name' => 'Asia', 'code' => null],
            ['name' => 'Europe', 'code' => null],
            ['name' => 'Africa', 'code' => null],
            ['name' => 'Australia and Oceania', 'code' => null],
        ];

        DB::table('countries')->insert($continents);
    }
}
