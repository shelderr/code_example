<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Links\AttachRequest;
use App\Http\Requests\General\Links\UpdateRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\General\LinksService;
use Illuminate\Http\Response;

class LinksController extends Controller
{
    private LinksService $service;

    public function __construct()
    {
        $this->service = resolve(LinksService::class);
        $this->middleware("auth:" . BaseAppGuards::USER);
    }

    public function attach(AttachRequest $request): Response
    {
        $data   = $request->validated();
        $detail = $this->service->attachLink($data);

        return response(compact('detail'), Response::HTTP_OK);
    }

    public function update(UpdateRequest $request, $id): Response
    {
        $data   = $request->validated();
        $detail = $this->service->update($data, $id);

        return response(compact('detail'), Response::HTTP_OK);
    }

    public function delete($id): Response
    {
        $this->service->delete($id);

        return \response()->noContent();
    }
}
