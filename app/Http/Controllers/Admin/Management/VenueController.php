<?php

namespace App\Http\Controllers\Admin\Management;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Venue\IndexRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Venue\VenueService;
use Illuminate\Http\Response;

class VenueController extends Controller
{
    private VenueService $service;

    public function __construct()
    {
        $this->service = resolve(VenueService::class);
        $this->middleware('auth:' . BaseAppGuards::ADMIN);
    }

    public function index(IndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $venues   = $this->service->getAll($paginate, false);

        return response(compact('venues'), Response::HTTP_OK);
    }

    public function blockSwitch($id): Response
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->blockSwitch();

        return \response()->noContent();
    }

    public function delete($id): Response
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->delete();

        return \response()->noContent();
    }
}
