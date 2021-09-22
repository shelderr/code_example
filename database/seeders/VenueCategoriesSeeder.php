<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collective;
use App\Models\Events\Event;
use Illuminate\Database\Seeder;

class VenueCategoriesSeeder extends Seeder
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
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Theatre',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Big Top',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Tent',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Arena',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Stadium',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Multisport Venue',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Street',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Open-Air',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Convention Center',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Ballroom',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Studio',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Mall',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Family Entertainment Center (FEC)',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Cruise Ship',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Night club',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Day Club',
                'parent_id' => null,
            ],
            [
                'type'      => Category::VENUE_CATEGORY,
                'name'      => 'Other',
                'parent_id' => null,
            ],
        ];

        \DB::table('categories')->insert($data);
    }

}
