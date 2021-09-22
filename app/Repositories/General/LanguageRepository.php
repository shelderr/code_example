<?php

declare(strict_types=1);

namespace App\Repositories\General;

use App\Models\Language;
use App\Repositories\Base\Repository;
use Illuminate\Support\Collection;

class LanguageRepository extends Repository
{
    public function model(): string
    {
        return Language::class;
    }

    public function index()
    {
        if (! \Cache::has('languages')) {
            return \Cache::remember('languages', '33600', function () {
                $languages =  $this->newQuery()->orderBy('name', 'asc')->get();

                return $this->sortCollection($languages);
            });
        }

        return \Cache::get('languages');
    }

    /**
     * Move other languages to top of the list
     *
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function sortCollection(Collection $collection): Collection
    {
        $languages = $collection->reject(
            function ($value) {
                return $value['code'] == 'other';
            }
        );

        $otherLanguages = $collection->filter(
            function ($value) {
                return $value['code'] == 'other';
            }
        );

        return $otherLanguages->merge($languages);
    }
}
