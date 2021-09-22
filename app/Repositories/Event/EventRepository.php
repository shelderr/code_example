<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Models\Events\Event;
use App\Repositories\Base\Repository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRepository extends Repository
{
    use UploadTrait;

    public function model(): string
    {
        return Event::class;
    }

    public function getEvents(string $type, int $paginate, bool $withScopes = true)
    {
        $events = $this->newQuery()
            ->with(Event::ALL_RELATIONS)
            ->where('type', '=', $type)
            ->imagesFirstly();

        if ($withScopes) {
            return $events->paginate($paginate);
        } else {
            return $events->withoutGlobalScopes()->paginate($paginate);
        }
    }

    /**
     * @param string $type
     * @param int    $paginate
     * @param array  $sorting
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function sortingRequestBuilder(string $type, int $paginate, array $sorting): LengthAwarePaginator
    {
        if (isset($sorting['order'])) {
            $order = $sorting['order'];
        }

        $events = $this->newQuery()->where('type', '=', $type)->where(
            function (Builder $q) use ($sorting) {
                if (isset($sorting['year_established'])) {
                    $q->where('established_year', '>=', $sorting['year_established']['min'])
                        ->where('established_year', '<=', $sorting['year_established']['max']);
                }
                if (isset($sorting['is_active'])) {
                    $q->whereNotNull('is_active')
                        ->where('is_active', '=', $sorting['is_active']);
                }

                if (isset($sorting['with_photo']) && $sorting['with_photo']) {
                    $q->whereNotNull('poster');
                }

                if (isset($sorting['show_type_ids'])) {
                    $q->whereHas(
                        Event::RELATION_SHOW_TYPES,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('category_id', $sorting['show_type_ids']);
                        }
                    );
                }

                if (isset($sorting['production_type_ids'])) {
                    $q->whereHas(
                        Event::RELATION_PRODUCTION_TYPE,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('category_id', $sorting['production_type_ids']);
                        }
                    );
                }

                if (isset($sorting['show_audience_ids'])) {
                    $q->whereHas(
                        Event::RELATION_SHOW_AUDIENCE,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('id', $sorting['show_audience_ids']);
                        }
                    );
                }

                if (isset($sorting['country_created_ids'])) {
                    $q->whereHas(
                        Event::RELATION_COUNTRIES_CREATED,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('country_id', $sorting['country_created_ids']);
                        }
                    );
                }

                if (isset($sorting['country_presented_ids'])) {
                    $q->whereHas(
                        Event::RELATION_COUNTRIES_PRESENTED,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('country_id', $sorting['country_presented_ids']);
                        }
                    );
                }

                if (isset($sorting['language_ids'])) {
                    $q->whereHas(
                        Event::RELATION_LANGUAGES,
                        function (Builder $q) use ($sorting) {
                            $q->whereIn('language_id', $sorting['language_ids']);
                        }
                    );
                }

                if (isset($sorting['alphabetical'])) {
                    $query = trim(mb_strtolower($sorting['alphabetical'])) . '%';

                    $q->whereRaw("LOWER(title) LIKE ?", [$query]);
                }
            }
        )->with(Event::ALL_RELATIONS);

        if (isset($sorting['rating'])) {
            return $events->ratingSorting($sorting['rating'])->paginate($paginate);
        }

        return $events->orderBy('created_at', $order ?? 'desc')->paginate($paginate);
    }

    /**
     * @param string $query
     * @param        $pagination
     *
     * @param string $type
     *
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(string $query, $pagination, string $type)
    {
        $query  = trim(mb_strtolower($query));
        $search = escapeLike($query);

        return $this->newQuery()->where('type', '=', $type)->where(
            function ($q) use ($search) {
                $q->whereRaw(
                    'LOWER(title) LIKE ?',
                    [$search]
                );
            }
        )->orderBy('created_at', 'desc')->paginate($pagination);
    }

    public function searchEditions(
        string $query,
        $pagination,
        string $type
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        $query  = trim(mb_strtolower($query));
        $search = escapeLike($query);

        return $this->newQuery()->where('type', '=', $type)
            ->where('is_original', '=', false)
            ->whereDoesntHave('parentEdition')
            ->where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(title) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($pagination);
    }

    public function updateGoogleMapEvents()
    {
        \Cache::delete('gmapsEvents');

        return \Cache::remember(
            'gmapsEvents',
            '7200',
            function () {
                return $this->getEventsWithVenueCoordinates();
            }
        );
    }

    public function getGoogleMapsEvents()
    {
        if (! \Cache::has('gmapsEvents')) {
            return \Cache::remember(
                'gmapsEvents',
                '7200',
                function () {
                    return $this->getEventsWithVenueCoordinates();
                }
            );
        }

        return \Cache::get('gmapsEvents');
    }

    private function getEventsWithVenueCoordinates(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->newQuery()
            ->where('is_active', '=', true)
            ->has(Event::RELATION_ACTIVE_VENUE)
            ->with(Event::RELATION_ACTIVE_VENUE)
            ->get();
    }
}
