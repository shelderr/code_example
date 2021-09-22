<?php

namespace App\Http\Controllers\Show;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateShowRequest;
use App\Http\Requests\Events\UpdateShowRequest;
use App\Http\Resources\Events\EventResource;
use App\Models\Events\Bookmarks\BookmarkFolder;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventService;
use Illuminate\Http\Response;

class ShowController extends Controller
{
    private EventService $service;

    public function __construct()
    {
        $this->service = resolve(EventService::class);
        $this->middleware(['auth:' . BaseAppGuards::USER])->except(['index', 'show', 'search']);
    }

    /**
     * @param \App\Http\Requests\Events\CreateEventRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(CreateShowRequest $request): Response
    {
        $createdEvent = $this->service->createEvent($request->validated());
        $event        = EventResource::make($createdEvent);

        return response(compact('event'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Events\UpdateShowRequest $request
     * @param                                              $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function update(UpdateShowRequest $request, $id): Response
    {
        $updatedEvent = $this->service->updateEvent($request->validated(), $id);
        $event        = EventResource::make($updatedEvent);

        return response(compact('event'), Response::HTTP_OK);
    }
}
