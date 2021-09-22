<?php

namespace App\Http\Controllers\Person;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\AttachToBookmarksRequest;
use App\Http\Requests\Person\Media\AttachImagesRequest;
use App\Http\Requests\Person\Media\AttachVideoRequest;
use App\Http\Requests\Person\Media\DeleteMediaRequest;
use App\Http\Requests\Person\Media\MediaRequest;
use App\Http\Requests\Person\PersonCreateRequest;
use App\Http\Requests\Person\PersonIndexRequest;
use App\Http\Requests\Person\PersonUpdateRequest;
use App\Http\Requests\Person\SearchRequest;
use App\Http\Requests\Person\ShowRequest;
use App\Http\Resources\Person\PersonResource;
use App\Services\Base\BaseAppGuards;
use App\Services\Person\PersonService;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    private PersonService $service;

    public function __construct()
    {
        $this->service = resolve(PersonService::class);
        $this->middleware('auth:' . BaseAppGuards::USER)->except(
            [
                'index',
                'show',
                'getImages',
                'getVideos',
                'getHeadshots',
            ]
        );
    }

    public function index(PersonIndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $sorting  = $data['sorting'] ?? null;
        $persons  = $this->service->getPersons($paginate, $sorting);

        return \response(compact('persons'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\SearchRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $persons  = $this->service->search($data['search'], $paginate);

        return response(compact('persons'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\ShowRequest $request
     * @param                                       $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, $id): Response
    {
        $foundedPerson = $this->service->newQuery()
            ->findOrFail($id);
        $person        = PersonResource::make($foundedPerson);

        return \response(compact('person'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\PersonCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(PersonCreateRequest $request): Response
    {
        $data   = $request->validated();
        $person = $this->service->create($data);

        return response(compact('person'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\PersonUpdateRequest $request
     * @param                                               $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(PersonUpdateRequest $request, $id): Response
    {
        $data   = $request->validated();
        $person = $this->service->update($data, $id);

        return response(compact('person'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Person\AttachToBookmarksRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function addToBookmark(AttachToBookmarksRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachToBookmark($data['person_id'], $data['folder_id']);

        return \response()->noContent();
    }

    public function attachMediaImage(AttachImagesRequest $request): Response
    {
        $media = $this->service->uploadMedia($request->validated());

        return \response(compact('media'), Response::HTTP_OK);
    }

    public function attachMediaVideo(AttachVideoRequest $request): Response
    {
        $media = $this->service->uploadMedia($request->validated());

        return \response(compact('media'), Response::HTTP_OK);
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
        $images   = $this->service->getImages($data['person_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }

    public function getVideos(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $videos   = $this->service->getVideos($data['person_id'], $paginate);

        return \response(compact('videos'), Response::HTTP_OK);
    }

    public function getHeadshots(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $images   = $this->service->getHeadshots($data['person_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }
}
