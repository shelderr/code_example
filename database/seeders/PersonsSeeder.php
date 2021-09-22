<?php

namespace Database\Seeders;

use App\Models\Persons;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class PersonsSeeder extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Persons::factory(100)->create();
    }
}
