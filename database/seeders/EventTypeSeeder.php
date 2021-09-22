<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Events\Event;
use App\Models\Roles;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
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
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Conference',
                'parent_id' => null,
            ],
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Festival',
                'parent_id' => null,
            ],
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Other',
                'parent_id' => null,
            ],
       ];

        \DB::table('categories')->insert($data);

        $concertId = $this->getParentId('Festival');

        $subCategoriesData = [
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Music Festival',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Circus Festival',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Dance Festival',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::EVENT_TYPE_CATEGORY,
                'name'      => 'Theatre Festival',
                'parent_id' => $concertId,
            ]
        ];

        \DB::table('categories')->insert($subCategoriesData);
    }

    private function getParentId(string $name): int
    {
        return Category::where('name', '=', $name)
            ->where('type', '=', Event::EVENT_TYPE_CATEGORY)
            ->first()->id;
    }
}
