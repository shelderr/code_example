<?php

declare(strict_types=1);

namespace App\Repositories\General;

use App\Exceptions\Http\BadRequestException;
use App\Models\Country;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class CountryRepository extends Repository
{
    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
    }

    public function model(): string
    {
        return Country::class;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (! \Cache::has('countries')) {
            return \Cache::remember(
                'countries',
                '33600',
                function () {
                    $collection = $this->newQuery()->orderBy('id', 'asc')->get();

                    return $this->sortCollection($collection);
                }
            );
        }

        return \Cache::get('countries');
    }

    /**
     * Sorting countries and continents
     *
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function sortCollection(Collection $collection): Collection
    {
        $countries = $collection->reject(
            function ($value) {
                return $value['code'] == null;
            }
        );

        $continents = $collection->filter(
            function ($value) {
                return $value['code'] == null;
            }
        );

        return $continents->merge($countries);
    }
}
