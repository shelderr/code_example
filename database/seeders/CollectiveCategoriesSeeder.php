<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collective;
use App\Models\Events\Event;
use Illuminate\Database\Seeder;

class CollectiveCategoriesSeeder extends Seeder
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
                'type'      => Collective::CATEGORIES,
                'name'      => 'Circus',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Music',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Dance',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Theatre',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Street',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Social',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Visual Arts',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Technology',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Performance',
                'parent_id' => null,
            ],
            [
                'type'      => Collective::CATEGORIES,
                'name'      => 'Other',
                'parent_id' => null,
            ],
        ];

        \DB::table('categories')->insert($data);
    }

}
