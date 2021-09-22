<?php

namespace App\Http\Controllers\Collective;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collective\AttachToBookmarkRequest;
use App\Http\Requests\Collective\CreateCollectiveRequest;
use App\Http\Requests\Collective\IndexRequest;
use App\Http\Requests\Collective\Media\AttachImagesRequest;
use App\Http\Requests\Collective\Media\AttachVideoRequest;
use App\Http\Requests\Collective\Media\DeleteMediaRequest;
use App\Http\Requests\Collective\Media\MediaRequest;
use App\Http\Requests\Collective\Persons\AttachPersonRequest;
use App\Http\Requests\Collective\Persons\DeletePersonRequest;
use App\Http\Requests\Collective\Persons\UpdateAttachedPersonRequest;
use App\Http\Requests\Collective\ShowCollectiveRequest;
use App\Http\Requests\Collective\UpdateCollectiveRequest;
use App\Http\Resources\Collective\CollectiveResource;
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
        $this->middleware("auth:" . BaseAppGuards::USER)->except(
            [
                'index',
                'show',
                'getImages',
                'getVideos',
                'getHeadshots',
            ]
        );
    }

    public function index(IndexRequest $request): Response
    {
        $data        = $request->validated();
        $paginate    = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $sorting     = $data['sorting'] ?? null;
        $collectives = $this->service->getCollectives($paginate, $sorting);

        return response(compact('collectives'), Response::HTTP_OK);
    }

    public function show(ShowCollectiveRequest $request, int $id): Response
    {
        $foundedCollective = $this->service->newQuery()
            ->findOrFail($id);
        $collective        = CollectiveResource::make($foundedCollective);

        return \response(compact('collective'), Response::HTTP_OK);
    }

    public function create(CreateCollectiveRequest $request): Response
    {
        $data       = $request->validated();
        $collective = $this->service->create($data);

        return response(compact('collective'), Response::HTTP_OK);
    }

    public function update(UpdateCollectiveRequest $request, $id): Response
    {
        $data       = $request->validated();
        $collective = $this->service->update($data, $id);

        return \response(compact('collective'), Response::HTTP_OK);
    }

    public function attachPerson(AttachPersonRequest $request): Response
    {
        $data           = $request->validated();
        $attachedPerson = $this->service->attachPerson($data);

        return \response(compact('attachedPerson'), Response::HTTP_OK);
    }

    public function editAttachedPerson(UpdateAttachedPersonRequest $request): Response
    {
        $data           = $request->validated();
        $attachedPerson = $this->service->editPerson($data);

        return \response(compact('attachedPerson'), Response::HTTP_OK);
    }

    public function deletePerson(DeletePersonRequest $request): Response
    {
        $data = $request->validated();
        $this->service->deletePerson($data);

        return \response()->noContent();
    }

    public function addToBookmarks(AttachToBookmarkRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachToBookmark($data['collective_id'], $data['folder_id']);

        return \response()->noContent();
    }

    public function attachMediaImage(AttachImagesRequest $request): Response
    {
        $this->service->uploadMedia($request->validated());

        return \response()->noContent();
    }

    public function attachMediaVideo(AttachVideoRequest $request): Response
    {
        $this->service->uploadMedia($request->validated());

        return \response()->noContent();
    }

    public function detachMediaItem(DeleteMediaRequest $request): Response
    {
        $this->service->deleteMedia($request->validated());

        return \response()->noContent();
    }

    public function getImages(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $images   = $this->service->getImages($data['collective_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }

    public function getVideos(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $videos = $this->service->getVideos($data['collective_id'], $paginate);

        return \response(compact('videos'), Response::HTTP_OK);
    }

    public function getHeadshots(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $images   = $this->service->getHeadshots($data['collective_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }
}
