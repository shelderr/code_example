<?php

namespace App\Http\Controllers\Admin\Management;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateEventRequest;
use App\Http\Requests\Events\IndexRequest;
use App\Http\Requests\Events\UpdateEventRequest;
use App\Http\Resources\Events\EventResource;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventService;
use Illuminate\Http\Response;

class EventController extends Controller
{
    private EventService $service;

    public function __construct()
    {
        $this->service = resolve(EventService::class);
        $this->middleware('auth:' . BaseAppGuards::ADMIN);
    }

    public function index(IndexRequest $request): Response
    {
        $pagination = $request->validated()['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $type       = $request->validated()['type'];
        $sorting    = $request->validated()['sorting'] ?? null;
        $events     = $this->service->index($type, $pagination, $sorting, false);

        return response(compact('events'), Response::HTTP_OK);
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

    public function blockSwitch($id): Response
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->blockSwitch();

        return \response()->noContent();
    }

    public function delete($id): Response
    {
        $this->service->newQuery()->withoutGlobalScopes()->findOrFail($id)->delete();

        return \response()->noContent();
    }
}
