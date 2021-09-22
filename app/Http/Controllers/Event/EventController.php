<?php

namespace App\Http\Controllers\Event;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Media\DeleteMediaRequest;
use App\Http\Requests\Events\ApplaudRequest;
use App\Http\Requests\Events\CreateEventRequest;
use App\Http\Requests\Events\IndexRequest;
use App\Http\Requests\Events\Media\AttachImagesRequest;
use App\Http\Requests\Events\Media\AttachVideoRequest;
use App\Http\Requests\Events\Media\MediaRequest;
use App\Http\Requests\Events\Persons\AttachPersonRequest;
use App\Http\Requests\Events\Persons\DetachPersonRequest;
use App\Http\Requests\Events\SearchReqeust;
use App\Http\Requests\Events\ShowRequest;
use App\Http\Requests\Events\UpdateEventRequest;
use App\Http\Requests\Events\Venues\AttachVenueRequest;
use App\Http\Requests\Events\Venues\DetachVenueRequest;
use App\Http\Requests\User\Bookmarks\AttachBookmarkRequest;
use App\Http\Resources\Events\EventResource;
use App\Models\Events\Bookmarks\BookmarkFolder;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventService;
use Illuminate\Http\Response;

class EventController extends Controller
{
    private EventService $service;

    public function __construct()
    {
        $this->service = resolve(EventService::class);
        $this->middleware(['auth:' . BaseAppGuards::USER])->except(
            [
                'index',
                'show',
                'search',
                'getGoogleMapsEvents',
                'getImages',
                'getVideos',
                'getPosters'
            ]
        );
    }

    /**
     * @param \App\Http\Requests\Events\IndexRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $type     = $data['type'];
        $sorting  = $data['sorting'] ?? null;
        $events   = $this->service->index($type, $paginate, $sorting, true);

        return \response(compact('events'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\CreateEventRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(CreateEventRequest $request): Response
    {
        $createdEvent = $this->service->createEvent($request->validated());
        $event        = EventResource::make($createdEvent);

        return response(compact('event'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\UpdateEventRequest $request
     * @param                                              $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function update(UpdateEventRequest $request, $id): Response
    {
        $updatedEvent = $this->service->updateEvent($request->validated(), $id);
        $event        = EventResource::make($updatedEvent);

        return response(compact('event'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\ShowRequest $request
     * @param int                                   $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, int $id): Response
    {
        $foundedEvent = $this->service->show($id);
        $event        = EventResource::make($foundedEvent);

        return \response(compact('event'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\ApplaudRequest $request
     * @param int                                      $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function applaud(ApplaudRequest $request, int $id): Response
    {
        $rating = $this->service->applaud($id, $request->validated()['rating']);

        return response(compact('rating'), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function deleteApplaud($id): Response
    {
        $this->service->deleteApplaud($id);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\User\Bookmarks\AttachBookmarkRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function addToBookmark(AttachBookmarkRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachToBookmark($data['event_id'], $data['folder_id']);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\Events\SearchReqeust $request
     *
     * @return \Illuminate\Http\Response
     */
    public function search(SearchReqeust $request): Response
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $events   = $this->service->search($data['search'], $paginate, $data['type']);

        return response(compact('events'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\Persons\AttachPersonRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException|\Throwable
     */
    public function attachPersons(AttachPersonRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachRoles($data);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\Events\Persons\DetachPersonRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function detachPerson(DetachPersonRequest $request): Response
    {
        $data = $request->validated();

        $this->service->detachRole($data['event_person_id']);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\Events\Venues\AttachVenueRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException|\Throwable
     */
    public function attachVenue(AttachVenueRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachVenue($data);

        return \response()->noContent();
    }

    /**
     * @param \App\Http\Requests\Events\Venues\DetachVenueRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function detachVenue(DetachVenueRequest $request): Response
    {
        $data = $request->validated();

        $this->service->detachVenue($data['event_id'], $data['venue_id']);

        return \response()->noContent();
    }

    public function getImages(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $images   = $this->service->getImages($data['event_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }

    public function getPosters(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $images   = $this->service->getPosters($data['event_id'], $paginate);

        return \response(compact('images'), Response::HTTP_OK);
    }

    public function getVideos(MediaRequest $request)
    {
        $data     = $request->validated();
        $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $videos   = $this->service->getVideos($data['event_id'], $paginate);

        return \response(compact('videos'), Response::HTTP_OK);
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

    public function getGoogleMapsEvents(): Response
    {
        $events = $this->service->getGoogleMapsEvents();

        return \response(compact('events'), Response::HTTP_OK);
    }
}
