<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Events\Event;
use App\Models\Roles;
use Illuminate\Database\Seeder;

class ShowTypeSeeder extends Seeder
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
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Dance',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Circus Show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Musical Theatre',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Variety',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Burlesque',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Concert',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Drama',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Opera',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Ballet',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Comedy',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Stand-up Comedy',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Fashion Show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Immersive Theatre',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Mixed Type Show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Gala Show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Corporate Event',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Private Event',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Other',
                'parent_id' => null,
            ],
        ];

        \DB::table('categories')->insert($data);

        $concertId = $this->getParentId('Concert');

        $subCategoriesData = [
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Pop Music',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Classical Music',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Rock Music',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Electronic Music',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Jazz Music',
                'parent_id' => $concertId,
            ],
            [
                'type'      => Event::SHOW_TYPE_CATEGORY,
                'name'      => 'Folk Music',
                'parent_id' => $concertId,
            ],
        ];

        \DB::table('categories')->insert($subCategoriesData);
    }

    private function getParentId(string $name): int
    {
        return Category::where('name', '=', $name)
            ->where('type', '=', Event::SHOW_TYPE_CATEGORY)
            ->first()->id;
    }
}
