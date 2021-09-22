<?php

namespace App\Http\Controllers\General;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\Search\ElasticsearchRequest;
use App\Http\Requests\General\Search\SearchRequest;
use App\Services\General\SearchService;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    private SearchService $service;

    public function __construct()
    {
        $this->service = resolve(SearchService::class);
    }

    /**
     * @param \App\Http\Requests\General\Search\SearchRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request): Response
    {
        $data = $request->validated();

        // Target search for (Like event, show, person...)
        $target = [];

        if (isset($data['target'])) {
            $target = explode(',', $data['target']);
        }

        if (empty($target)) {
            $target = ['event', 'show', 'collective', 'person', 'venues'];
        }

        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $search   = $this->service->search($data['query'], $paginate, $target);

        return response(compact('search'), Response::HTTP_OK);
    }

    public function elasticsearch(ElasticsearchRequest $request): Response
    {
        $query = $request->validated()['search'];
        $data  = $this->service->elasticsearch($query);

        return \response($data, Response::HTTP_OK);
    }
}
