<?php

namespace App\Services\Event;

use App\Enums\BaseAppEnum;
use App\Exceptions\Http\BadRequestException;
use App\Models\Events\Event;
use App\Models\Events\EventAct;
use App\Models\Events\EventSeason;
use App\Traits\UploadTrait;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class EventEditionsSeasonsService extends EventService
{
    use UploadTrait;

    /**
     * @param int        $originalEventId
     * @param array|null $editionsIds
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachEdition(int $originalEventId, ?array $editionsIds): mixed
    {
        if (! is_null($editionsIds)) {
            abort_if(! isArrayInt($editionsIds), Response::HTTP_BAD_REQUEST, 'editions ids must be integer');
        }

        return DB::transaction(
            function () use ($originalEventId, $editionsIds) {
                $event = $this->findOrFail($originalEventId);

                abort_if($event->is_original == false, Response::HTTP_BAD_REQUEST, 'Event not original');

                $eventChildrenEditions = $event->whereHas(
                    'childrenEditions',
                    function ($q) use ($editionsIds) {
                        $q->whereIn('id', $editionsIds);
                    }
                );

                if ($eventChildrenEditions->exists()) {
                    throw new BadRequestException('event already have this editions', Response::HTTP_NOT_ACCEPTABLE);
                }
                // $this->newQuery()->where('parent_id', '=', $event->id)->update(['parent_id' => null]);
                //TODO::ЗАпретить возможность выбрать edition который прикреплен к другому ивенту
                if (! is_null($editionsIds)) {
                    $this->newQuery()->whereIn('id', $editionsIds)
                        ->update(['parent_id' => $event->id]);
                }

                return $event;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function detachEdition(int $editionId)
    {
        return DB::transaction(
            function () use ($editionId) {
                $edition =  $this->newQuery()->where('is_original', '=', false)
                    ->where('id', '=', $editionId);

                $edition->update(['parent_id' => null]);

                return $edition->get();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachSeason(array $data): mixed
    {
        return DB::transaction(
            function () use ($data) {
                $event = $this->findOrFail($data['event_id']);

                if (isset($data['image'])) {
                    $bannerName = Str::slug($event->title) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadOne($data['image'], Event::SEASONS_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $bannerName);

                    $data['image'] = Event::SEASONS_FOLDER . $bannerName;
                }

                return EventSeason::create($data);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param int   $seasonId
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function editSeason(int $seasonId, array $data): mixed
    {
        return DB::transaction(
            function () use ($seasonId, $data) {
                $season = EventSeason::findOrFail($seasonId);

                if (array_key_exists('image', $data)) {
                    $name = Str::slug($season->title) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadOne($data['image'], Event::SEASONS_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $name);

                    $data['image'] = Event::SEASONS_FOLDER . $name;

                    EventSeason::$withoutUrl = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($season->image);

                    EventSeason::$withoutUrl = false;
                }

                return $season->update($data);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function deleteSeason(int $seasonId)
    {
        return DB::transaction(
            function () use ($seasonId) {
                $season = EventSeason::findOrFail($seasonId);

                if (! is_null($season->image)) {
                    EventSeason::$withoutUrl = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($season->image);

                    EventSeason::$withoutUrl = false;
                }

                return $season->delete();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
