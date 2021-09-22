<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\BaseAppEnum;

trait PaginateCollectionTrait
{
    /**
     * @param        $collection
     * @param        $perPage
     * @param string $pageName
     * @param null   $fragment
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginateCollection($collection, $perPage, $pageName = 'page', $fragment = null)
    {
        $currentPage      = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);

        if (is_null(request()->getQueryString())) {
            $query = ['paginate' => BaseAppEnum::DEFAULT_PAGINATION];
        } else {
            parse_str(request()->getQueryString(), $query);

            unset($query[$pageName]);
        }

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path'     => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query'    => $query,
                'fragment' => $fragment,
            ]
        );

        return $paginator;
    }
}
