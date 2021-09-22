<?php

namespace App\Http\Controllers\Admin\Management;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collective\IndexRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Collective\CollectiveService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectiveController extends Controller
{
    private CollectiveService $service;

    public function __construct()
    {
        $this->service = resolve(CollectiveService::class);
        $this->middleware("auth:" . BaseAppGuards::ADMIN);
    }

    public function index(IndexRequest $request): Response
    {
        $data        = $request->validated();
        $paginate    = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $collectives = $this->service->getCollectives($paginate, null);

        return response(compact('collectives'), Response::HTTP_OK);
    }

    public function blockSwitch($id): Response
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->blockSwitch();

        return \response()->noContent();
    }

    public function delete($id)
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->delete();

        return \response()->noContent();
    }
}
