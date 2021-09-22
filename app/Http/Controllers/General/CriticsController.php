<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Critics\AttachRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\General\CriticsService;
use Illuminate\Http\Response;

class CriticsController extends Controller
{
    private CriticsService $service;

    public function __construct()
    {
        $this->service = resolve(CriticsService::class);
        $this->middleware("auth:" . BaseAppGuards::USER);
    }

    public function attach(AttachRequest $request): Response
    {
        $data   = $request->validated();
        $critic = $this->service->attachCritics($data);

        return response(compact('critic'), Response::HTTP_OK);
    }

    public function delete($id): Response
    {
        $this->service->delete($id);

        return \response()->noContent();
    }
}
