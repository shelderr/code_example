<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

class PersonalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parentsData = [
            [
                'name' => 'performer',
            ],
            [
                'name' => 'creator',
            ],
            [
                'name' => 'operations/crew',
            ],
        ];

        \DB::table('roles')->insert($parentsData);

        $performerId      = $this->getParentId($parentsData[0]['name'] );
        $creatorId        = $this->getParentId($parentsData[1]['name']);
        $operationsCrewId = $this->getParentId($parentsData[2]['name']);

        $subCategories = [
            [
                'name'      => 'Athlete',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Circus Performer',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Dancer',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Musician',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Singer',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Clown',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Actor',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Model',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Variety Artist',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Performance Artist',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Visual Artist',
                'parent_id' => $performerId,
            ],
            [
                'name'      => 'Other',
                'parent_id' => $performerId,
            ],
            //Creator
            [
                'name'      => 'Stylist',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Scenographer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Set Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Manager',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Executive',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Musical Director',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Acrobatic Equipment Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Agent',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Director',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Editor',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Scenographer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Photographer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Sculptor',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Painter',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Props/Accessories Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Creative Guide',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Stage Director',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Assistant Director',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Director of Creation',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Executive Producer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Producer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Director of Production',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Writer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Choreographer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Costume Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Make-up Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Lighting Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Sound Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Acrobatic Performance Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Event Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Interiour Designer',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Architect',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Director',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Advisor',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Associate',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Specialist',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Talent Scout',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Specialist',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Casting Coordinator',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Illustrator',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Draftsperson',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Tentmaster',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Project Manager',
                'parent_id' => $creatorId,
            ],
            [
                'name'      => 'Other',
                'parent_id' => $creatorId,
            ],

            //CREW
            [
                'name'      => 'Senior Artistoc Director',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Artistic Director',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Artistic Assistant',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Resident Director',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Technical Director',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'General Stage Manager',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Stage Manager',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Backstage Manager',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Head of Lighting',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Lighting',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Head of Sound',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Sound',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Head Rigger',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Rigger',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Technitian',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Automation',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Tour Manager',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Head Coach',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Coach',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Head of Wardrobe',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Wardrobe',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Physiotherapist',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Massage Therapist',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Front of House',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Production Manager',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Production Assistant',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Carpenter',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Painter',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Finance',
                'parent_id' => $operationsCrewId,
            ],
            [
                'name'      => 'Other',
                'parent_id' => $operationsCrewId,
            ],
        ];

        \DB::table('roles')->insert($subCategories);
    }

    private function getParentId(string $name): int
    {
        return Roles::where('name', '=', $name)
            ->first()->id;
    }
}
