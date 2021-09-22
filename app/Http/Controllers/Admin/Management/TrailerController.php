<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Trailers\CreateTrailerRequest;
use App\Http\Requests\Events\Trailers\UpdateTrailerRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventService;
use Illuminate\Http\Response;

class TrailerController extends Controller
{
    private EventService $service;

    public function __construct()
    {
        $this->service = resolve(EventService::class);
        $this->middleware('auth:'. BaseAppGuards::ADMIN);
    }

    /**
     * @param \App\Http\Requests\Events\Trailers\CreateTrailerRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function create(CreateTrailerRequest $request): Response
    {
        $this->service->attachTrailers($request->validated());

        return response()->noContent();
    }

    /**
     * @param \App\Http\Requests\Events\Trailers\UpdateTrailerRequest $request
     * @param                                                         $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(UpdateTrailerRequest $request, $id): Response
    {
        $trailer = $this->service->updateTrailer($request->validated(), $id);

        return \response(compact('trailer'), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function delete($id)
    {
        $this->service->deleteTrailer($id);

        return \response()->noContent();
    }
}
