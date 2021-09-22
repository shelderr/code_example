<?php

namespace App\Http\Controllers\Media;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\IndexRequest;
use App\Services\Media\MediaService;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    private MediaService $service;

    public function __construct()
    {
        $this->service = resolve(MediaService::class);
    }

    public function index(IndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $media    = $this->service->index($data['type'])->paginate($paginate);

        return response(compact('media'), Response::HTTP_OK);
    }
}
