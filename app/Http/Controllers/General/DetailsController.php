<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Details\AttachRequest;
use App\Http\Requests\General\Details\UpdateRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\General\DetailsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DetailsController extends Controller
{
    private DetailsService $service;

    public function __construct()
    {
        $this->service = resolve(DetailsService::class);
        $this->middleware("auth:" . BaseAppGuards::USER);
    }

    public function attach(AttachRequest $request): Response
    {
        $data   = $request->validated();
        $detail = $this->service->attachDetail($data);

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
