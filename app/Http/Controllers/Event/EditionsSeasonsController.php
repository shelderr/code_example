<?php

namespace App\Http\Controllers\Event;

use App\Enums\BaseAppEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Editions\AttachEditionRequest;
use App\Http\Requests\Events\Editions\DetachEditionRequest;
use App\Http\Requests\Events\SearchReqeust;
use App\Http\Requests\Events\Seasons\AttachSeasonRequest;
use App\Http\Requests\Events\Seasons\EditSeasonRequest;
use App\Services\Base\BaseAppGuards;
use App\Services\Event\EventEditionsSeasonsService;
use Illuminate\Http\Response;

class EditionsSeasonsController extends Controller
{

    private EventEditionsSeasonsService $service;

    public function __construct()
    {
        $this->service = resolve(EventEditionsSeasonsService::class);
        $this->middleware(['auth:' . BaseAppGuards::USER])->except(['index', 'show', 'search']);
    }

    public function attachEdition(AttachEditionRequest $request): Response
    {
        $data  = $request->validated();
        $event = $this->service->attachEdition($data['original_event_id'], $data['edition_event_ids'] ?? null);

        return response(compact('event'), Response::HTTP_OK);
    }

    public function detachEdition(DetachEditionRequest $request): Response
    {
        $editionId = $request->validated()['edition_event_id'];
        $edition   = $this->service->detachEdition($editionId);

        return response(compact('edition'), Response::HTTP_OK);
    }

    public function attachSeasons(AttachSeasonRequest $request): Response
    {
        $data = $request->validated();

        $this->service->attachSeason($data);

        return \response()->noContent();
    }

    public function editSeason(EditSeasonRequest $request, int $id): Response
    {
        $data = $request->validated();

        $this->service->editSeason($id, $data);

        return \response()->noContent();
    }

    public function deleteSeason($id): Response
    {
        $this->service->deleteSeason($id);

        return \response()->noContent();
    }

    public function searchByEditions(SearchReqeust $request): Response
    {
        $data     = $request->validated();
        $editions = $this->service->searchEditions(
            $data['search'],
            $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION,
            $data['type']
        );

        return response(compact('editions'), Response::HTTP_OK);
    }
}
