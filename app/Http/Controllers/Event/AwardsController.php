<?php

namespace App\Http\Controllers\Event;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Awards\CreateAwardRequest;
use App\Http\Requests\Events\Awards\IndexRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\AwardsService;
use Illuminate\Http\Response;

class AwardsController extends Controller
{
    private AwardsService $service;

    public function __construct()
    {
        $this->middleware(['auth:' . BaseAppGuards::USER]);
        $this->service = resolve(AwardsService::class);
    }

    public function index(IndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $awards   = $this->service->index($data['event_id'], $paginate, $data['type']);

        return \response(compact('awards'), Response::HTTP_OK);
    }

    /**
     * @throws \Throwable
     */
    public function create(CreateAwardRequest $request): Response
    {
        $data  = $request->validated();
        $award = $this->service->createAward($data);

        return \response(compact('award'), Response::HTTP_OK);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }
}
