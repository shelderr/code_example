<?php

namespace Database\Seeders;

use App\Models\Events\Event;
use Illuminate\Database\Seeder;

class ProductionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Theatre - resident',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Theatre - touring',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Big Top - touring',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Spiegeltent - touring',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Spiegeltent - resident',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Arena',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Dinner show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Cruise ship',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Night Club',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Convention center',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Open-air',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Stadium',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Immersive theatre format',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Mixed format',
                'parent_id' => null,
            ],
            [
                'type'      => Event::PRODUCTION_TYPE_CATEGORY,
                'name'      => 'Other',
                'parent_id' => null,
            ],
        ];

        \DB::table('categories')->insert($data);
    }
}
