<?php

namespace App\Http\Controllers\Person;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\Roles\IndexRequest;
use App\Http\Requests\Person\SearchRequest;
use App\Services\Person\RolesService;
use Illuminate\Http\Response;

class RolesController extends Controller
{
    private RolesService $service;

    public function __construct()
    {
        $this->service = resolve(RolesService::class);
    }

    public function index(IndexRequest $request)
    {
        $data  = $request->validated();
        $roles = $this->service->index($data['role_id'] ?? null);

        return response(compact('roles'), Response::HTTP_OK);
    }

    public function search(SearchRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $persons  = $this->service->search($data['search'], $paginate);

        return response(compact('persons'), Response::HTTP_OK);
    }
}
