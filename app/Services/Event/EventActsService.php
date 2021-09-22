<?php

namespace App\Services\Event;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Models\Events\Event;
use App\Models\Events\EventAct;
use DB;
use Illuminate\Support\Str;

class EventActsService extends EventService
{
    public function attachAct(array $data)
    {
        DB::transaction(
            function () use ($data) {
                $event          = $this->findOrFail($data['event_id']);
                $personsIds     = $data['persons_ids'] ?? null;
                $showsIds       = $data['shows_ids'] ?? null;
                $collectivesIds = $data['collectives_ids'] ?? null;
                $awardIds       = $data['award_ids'] ?? null;

                unset($data['persons_ids'], $data['collectives_ids'], $data['shows_ids']);

                if ($event->type == Event::TYPE_SHOW && isset($data['is_future'])) {
                    throw new BadRequestException("is_future not acceptable in $event->type");
                }

                if (isset($data['image'])) {
                    $photo = Str::slug($data['title']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadOne($data['image'], $this->model()::ACTS_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $photo);

                    $data['image'] = $this->model()::ACTS_FOLDER . $photo;
                }

                $eventAct = EventAct::create($data);

                if (! is_null($personsIds)) {
                    $eventAct->persons()->attach($personsIds);
                }

                if (! is_null($collectivesIds)) {
                    $eventAct->collectives()->sync($collectivesIds);
                }
                
                if ($event->type == Event::TYPE_EVENT) {
                    if (! is_null($showsIds)) {
                        $eventAct->shows()->attach($showsIds);
                    }

                    if (! is_null($awardIds)) {
                        $eventAct->awards()->attach($awardIds);
                    }
                }

                return $eventAct;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @throws \Throwable
     */
    public function editAct(array $data, int $id)
    {
        DB::transaction(
            function () use ($data, $id) {
                $act            = EventAct::findOrFail($id);
                $personsIds     = $data['persons_ids'] ?? [];
                $showsIds       = $data['shows_ids'] ?? [];
                $collectivesIds = $data['collectives_ids'] ?? [];

                unset($data['persons_ids'], $data['shows_ids'], $data['collectives_ids']);

                if (array_key_exists('image', $data)) {
                    $name = Str::slug($data['title']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadOne($data['image'], $this->model()::ACTS_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $name);

                    $data['image'] = $this->model()::ACTS_FOLDER . $name;

                    EventAct::$withoutUrl = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($act->image);

                    EventAct::$withoutUrl = false;
                }

                $act->update($data);

                $act->persons()->sync($personsIds);

                $act->shows()->sync($showsIds);

                $act->collectives()->sync($collectivesIds);

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function deleteAct(int $id)
    {
        return DB::transaction(
            function () use ($id) {
                return EventAct::findOrFail($id)->delete();
            },
        );
    }
}
