<?php

namespace App\Http\Controllers\Venue;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Venue\AttachToBookmarkRequest;
use App\Http\Requests\Venue\CreateVenueRequest;
use App\Http\Requests\Venue\IndexRequest;
use App\Http\Requests\Venue\Media\AttachImagesRequest;
use App\Http\Requests\Venue\Media\DeleteMediaRequest;
use App\Http\Requests\Venue\ShowVenueRequest;
use App\Http\Requests\Venue\UpdateVenueRequest;
use App\Http\Resources\Venue\VenueResource;
use App\Services\Base\BaseAppGuards;
use App\Services\Venue\VenueMediaService;
use App\Services\Venue\VenueService;
use Illuminate\Http\Response;

class VenueController extends Controller
{
    private VenueService $service;

    private VenueMediaService $mediaService;

    public function __construct()
    {
        $this->service = resolve(VenueService::class);
        $this->mediaService = resolve(VenueMediaService::class);
        $this->middleware('auth:' . BaseAppGuards::USER)->except('index', 'show');
    }

    public function index(IndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $venues   = $this->service->getAll($paginate);

        return response(compact('venues'), Response::HTTP_OK);
    }

    public function show(ShowVenueRequest $request, $id): Response
    {
        $venue = $this->service->findOrFail($id);
        $venue = VenueResource::make($venue);

        return response(compact('venue'), Response::HTTP_OK);
    }

    public function create(CreateVenueRequest $request): Response
    {
        $data  = $request->validated();
        $venue = $this->service->create($data);

        return \response(compact('venue'), Response::HTTP_OK);
    }

    public function update(UpdateVenueRequest $request, $id): Response
    {
        $data  = $request->validated();
        $venue = $this->service->update($data, $id);

        return \response(compact('venue'), Response::HTTP_OK);
    }

    public function attachMediaImage(AttachImagesRequest $request): Response
    {
        $this->mediaService->uploadMedia($request->validated());

        return \response()->noContent();
    }

    public function detachMediaItem(DeleteMediaRequest $request): Response
    {
        $this->mediaService->deleteMedia($request->validated());

        return \response()->noContent();
    }

    public function addToBookmarks(AttachToBookmarkRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachToBookmark($data['venue_id'], $data['folder_id']);

        return \response()->noContent();
    }
}
