<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Acts\AttachActRequest;
use App\Http\Requests\Events\Acts\UpdateActRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventActsService;
use Illuminate\Http\Response;

class ActsController extends Controller
{
    private EventActsService $service;

    public function __construct()
    {
        $this->service = resolve(EventActsService::class);
        $this->middleware(['auth:' . BaseAppGuards::USER])->except('index');
    }

    public function attachAct(AttachActRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachAct($data);

        return response()->noContent();
    }

    public function updateAct(UpdateActRequest $request, $id): Response
    {
        $data = $request->validated();
        $this->service->editAct($data, $id);

        return response()->noContent();
    }

    public function deleteAct($id): Response
    {
        $this->service->deleteAct($id);

        return response()->noContent();
    }
}
