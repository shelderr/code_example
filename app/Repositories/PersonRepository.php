<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Persons\PersonEnum;
use App\Models\Persons;
use App\Models\User;
use App\Repositories\Base\Repository;
use App\Traits\PaginateCollectionTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PersonRepository extends Repository
{
    use PaginateCollectionTrait;

    public function model(): string
    {
        return Persons::class;
    }

    /**
     * @param int        $paginate
     *
     * @param array|null $sorting
     * @param bool       $withScopes
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPersons(int $paginate, ?array $sorting, bool $withScopes = true): LengthAwarePaginator
    {
        if (! is_null($sorting)) {
            return $this->sortingRequestBuilder($paginate, $sorting);
        }
        
        $persons = $this->newQuery()
            ->imagesFirstly()
            ->with(get_class_vars($this->model())['allRelations']);

        if ($withScopes) {
            return $persons->paginate($paginate);
        } else {
            return $persons->withoutGlobalScopes()->paginate($paginate);
        }
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

        $persons = $this->newQuery()->where(
            function ($q) use ($search) {
                $q->whereRaw(
                    'LOWER(name) LIKE ?',
                    [$search]
                );
            }
        )->orderBy('created_at', 'desc')->paginate($pagination);
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

                if (isset($sorting['role_ids'])) {
                    $q->whereHas(
                        'roles',
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('id', $sorting['role_ids']);
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

                if (isset($sorting['is_member'])) {
                    if ($sorting['is_member']) {
                        $q->whereHas(
                            Persons::LINKED_USER,
                            function (Builder $q) {
                                $q->where('status', '=', User::LINK_STATUS_ACCEPTED);
                            }
                        );
                    } else {
                        $q->whereHas(
                            Persons::LINKED_USER,
                            function () {
                            },
                            '=',
                            0
                        );
                    }
                }

                if (isset($sorting['with_photo']) && $sorting['with_photo']) {
                    $q->whereNotNull('image');
                }
            }
        )
        ->orderBy($orderBy, $order)
        ->with(get_class_vars($this->model())['allRelations'])
        ->paginate($paginate);
    }
}
