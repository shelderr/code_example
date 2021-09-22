<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\KYC\KycEnum;
use App\Models\Roles;
use App\Models\User;
use App\Models\User\KYC;
use App\Repositories\Base\Repository;
use Illuminate\Database\Eloquent\Builder;

class RolesRepository extends Repository
{
    public function model(): string
    {
        return Roles::class;
    }


    /**
     * @param string $search
     * @param int    $pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(string $search, int $pagination): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query  = trim(mb_strtolower($search));
        $search = escapeLike($query);

        return $this->newQuery()->where(
            function ($q) use ($search) {
                $q->whereRaw(
                    'LOWER(name) LIKE ?',
                    [$search]
                );
            }
        )->paginate($pagination);
    }
}
