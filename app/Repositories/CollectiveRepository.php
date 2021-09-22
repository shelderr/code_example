<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Persons\PersonEnum;
use App\Models\Collective;
use App\Models\Persons;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CollectiveRepository extends Repository
{
    public function model(): string
    {
        return Collective::class;
    }

    public function getCollectives(
        int $paginate,
        ?array $sorting,
        bool $withScopes = true
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        if (! is_null($sorting)) {
            return $this->sortingRequestBuilder($paginate, $sorting);
        }

        $collectives = $this->newQuery()
            ->with(Collective::ALL_RELATIONS)
            ->imagesFirstly();

        if ($withScopes) {
            return $collectives->paginate($paginate);
        } else {
            return $collectives->withoutGlobalScopes()->paginate($paginate);
        }
    }

    /**
     * @param int   $paginate
     * @param array $sorting
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function sortingRequestBuilder(int $paginate, array $sorting): LengthAwarePaginator
    {
        if (isset($sorting['order'])) {
            $orderBy = PersonEnum::CREATED_AT;
            $order   = $sorting['order'];
        } else {
            $orderBy = PersonEnum::ID;
            $order   = Persons::ORDER_ASC;
        }

        return $this->newQuery()->where(
            function (Builder $q) use ($sorting, $paginate) {
                if (isset($sorting['order'])) {
                    $q->orderBy('id', $sorting['order']);
                }

                if (isset($sorting['alphabetical'])) {
                    $query = trim(mb_strtolower($sorting['alphabetical'])) . '%';

                    $q->whereRaw("LOWER(name) LIKE ?", [$query]);
                }

                if (isset($sorting['category_ids'])) {
                    $q->whereHas(
                        Collective::CATEGORY_RELATION,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('id', $sorting['category_ids']);
                        }
                    );
                }

                if (isset($sorting['country_ids'])) {
                    $q->whereHas(
                        'countries',
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('country_id', $sorting['country_ids']);
                        }
                    );
                }
            }
        )
            ->orderBy($orderBy, $order)
            ->paginate($paginate);
    }
}
