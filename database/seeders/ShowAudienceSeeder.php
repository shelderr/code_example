<?php

namespace Database\Seeders;

use App\Models\Events\Event;
use Illuminate\Database\Seeder;

class ShowAudienceSeeder extends Seeder
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
                'type'      => Event::SHOW_AUDIENCE_CATEGORY,
                'name'      => 'Show for kids',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_AUDIENCE_CATEGORY,
                'name'      => 'Family show',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_AUDIENCE_CATEGORY,
                'name'      => 'Show for adults, kids allowed',
                'parent_id' => null,
            ],
            [
                'type'      => Event::SHOW_AUDIENCE_CATEGORY,
                'name'      => 'Show for adults (18+)',
                'parent_id' => null,
            ],
        ];

        \DB::table('categories')->insert($data);
    }
}
