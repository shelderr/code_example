<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Venue;
use App\Repositories\Base\Repository;

class VenueRepository extends Repository
{
    public function model(): string
    {
        return Venue::class;
    }

    public function getAll(int $paginate, bool $withScopes = true): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $venues =  $this->newQuery()
            ->imagesFirstly();

        if ($withScopes) {
            return $venues->paginate($paginate);
        } else {
            return $venues->withoutGlobalScopes()->paginate($paginate);
        }
    }
}
