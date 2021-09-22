<?php

namespace App\Services\Event;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Events\AlreadyApplauded;
use App\Exceptions\Http\BadRequestException;
use App\Models\Category;
use App\Models\Collective;
use App\Models\Country;
use App\Models\Events\EventPerson;
use App\Models\Events\EventPersonRole;
use App\Models\Events\EventVenue;
use App\Models\Media;
use App\Models\Persons;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Models\Events\Event;
use App\Models\Events\EventTrailers;
use App\Models\User;
use App\Models\Venue;
use App\Repositories\Event\EventRepository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EventService extends EventRepository
{
    use UploadTrait;

    private ?User $user;

    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
        $this->user = auth()->guard(BaseAppGuards::USER)->user();
    }

    /**
     * @param string     $type
     * @param int        $paginate
     *
     * @param array|null $sorting
     * @param bool       $wihScopes
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(string $type, int $paginate, ?array $sorting, bool $wihScopes): LengthAwarePaginator
    {
        if (! is_null($sorting)) {
            return parent::sortingRequestBuilder($type, $paginate, $sorting);
        }
        
        return $this->getEvents($type, $paginate, $wihScopes);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show(int $id): Model
    {
        return $this->findOrFail($id);
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function createEvent(array $data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $data = $this->establishedDateSaveModifier($data);

                if (isset($data['poster'])) {
                    $bannerName = Str::slug($data['title']) . Str::random(20) .
                        time() . '.' . $data['poster']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['poster'],
                        Event::POSTERS_FOLDER,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $bannerName
                    );

                    $data['poster'] = Event::POSTERS_FOLDER . $bannerName;
                }

                $event = $this->create($data);

                if (isset($data['countries_created'])) {
                    $event->countriesCreated()->attach(
                        $data['countries_created'],
                        ['country_type' => Country::EVENT_COUNTRY_CREATED_TYPE]
                    );
                }

                if (isset($data['countries_presented'])) {
                    $event->countriesPresented()->attach(
                        $data['countries_presented'],
                        ['country_type' => Country::EVENT_COUNTRY_PRESENTED_TYPE]
                    );
                }

                if (isset($data['production_types'])) {
                    $event->productionTypes()->attach($data['production_types']);
                }

                if (isset($data['languages'])) {
                    $event->languages()->attach($data['languages']);
                }

                if (isset($data['show_types'])) {
                    $showTypes = Category::findOrFail($data['show_types']);

                    $event->showTypes()->attach($showTypes);
                }

                if (isset($data['event_types'])) {
                    $showTypes = Category::findOrFail($data['event_types']);

                    $event->eventTypes()->attach($showTypes);
                }

                return $event->fresh();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Modification input date
     *
     * @param array $data
     *
     * @return array
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function establishedDateSaveModifier(array $data): array
    {
        if (isset($data['established_year']) && $data['type'] == Event::TYPE_SHOW) {
            $establishedDate = makeTrueDate(
                $data['established_year'],
                $data['established_month'] ?? null,
                $data['established_day'] ?? null
            );

            foreach ($establishedDate as $key => $date) {
                if (is_null($date)) {
                    unset($data["established_$key"]);
                } else {
                    $data["established_$key"] = $date;
                }
            }
        }

        return $data;
    }

    /**
     * @param array  $data
     * @param int    $id
     * @param string $attribute
     *
     * @return mixed
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function updateEvent(array $data, int $id, string $attribute = "id"): mixed
    {
        try {
            return \DB::transaction(
                function () use ($data, $id, $attribute) {
                    $event               = $this->findOrFail($id);
                    $countriesCreatedIds = $data['countries_created'] ?? null;
                    $countriesPresented  = $data['countries_presented'] ?? null;
                    $showTypes           = $data['show_types'] ?? null;
                    $eventTypes          = $data['event_types'] ?? null;
                    $productionTypes     = $data['production_types'] ?? null;
                    $languages           = $data['languages'] ?? null;

                    if (($event->type == Event::TYPE_SHOW && ! is_null($eventTypes)) ||
                        ($event->type == Event::TYPE_EVENT && ! is_null($showTypes))
                    ) {
                        throw new BadRequestException("Invalid $event->type types");
                    }
                    unset(
                        $data['countries_created'],
                        $data['countries_presented'],
                        $data['show_types'],
                        $data['event_types'],
                        $data['production_types'],
                        $data['languages']
                    );

                    $event = $this->findOrFail($id);
                    $data  = $this->establishedDateUpdateModifier($data, $event);

                    if (array_key_exists('poster', $data)) {
                        $name = Str::slug($data['title']) . Str::random(20) .
                            time() . '.' . $data['poster']->getClientOriginalExtension();

                        $this->uploadMultipleSizes(
                            $data['poster'],
                            Event::POSTERS_FOLDER,
                            BaseAppEnum::DEFAULT_DRIVER,
                            $name
                        );

                        $data['poster'] = Event::POSTERS_FOLDER . $name;

                        Event::$withoutUrl = true;

                        \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($event->poster);

                        if (! is_null($event->poster)) {
                            foreach ($event->multiSizeImages as $image) {
                                \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                            }
                        }

                        Event::$withoutUrl = false;
                    }

                    $event->update($data);

                    $event->countriesCreated()->detach();
                    $event->countriesPresented()->detach();
                    $event->showTypes()->detach();
                    $event->eventTypes()->detach();
                    $event->productionTypes()->detach();
                    $event->languages()->detach();

                    if (! is_null($countriesCreatedIds)) {
                        $event->countriesCreated()->attach(
                            $countriesCreatedIds,
                            ['country_type' => Country::EVENT_COUNTRY_CREATED_TYPE]
                        );
                    }

                    if (! is_null($countriesPresented)) {
                        $event->countriesPresented()->attach(
                            $countriesPresented,
                            ['country_type' => Country::EVENT_COUNTRY_PRESENTED_TYPE]
                        );
                    }

                    if (! is_null($productionTypes)) {
                        $event->productionTypes()->attach($productionTypes);
                    }

                    if (! is_null($showTypes)) {
                        $event->showTypes()->attach($showTypes);
                    }

                    if (! is_null($eventTypes)) {
                        $event->showTypes()->attach($eventTypes);
                    }

                    if (! is_null($languages)) {
                        $event->languages()->attach($languages);
                    }

                    return $event->fresh();
                },
                BaseAppEnum::TRANSACTION_ATTEMPTS
            );
        } catch (\Throwable $e) {
            throw new BadRequestException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Modification update
     *
     * @param array $data
     * @param       $event
     *
     * @return array
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function establishedDateUpdateModifier(array $data, $event): array
    {
        $establishedDate = makeTrueDate(
            $data['established_year'] ?? null,
            $data['established_month'] ?? null,
            $data['established_day'] ?? null
        );

        foreach ($establishedDate as $key => $date) {
            $data["established_$key"] = $date;
        }

        return $data;
    }

    /**
     * Applaud to event
     *
     * @param int $id
     * @param int $rating
     *
     * @return float
     * @throws \Throwable
     */
    public function applaud(int $id, int $rating): float
    {
        return \DB::transaction(
            function () use ($id, $rating) {
                $event = $this->findOrFail($id);

                if ($this->user->hasApplauds($event->id)) {
                    throw new AlreadyApplauded();
                }

                $event->applauds()->create(
                    [
                        'event_id' => $event->id,
                        'user_id'  => $this->user->id,
                        'rating'   => $rating,
                    ]
                );

                return $event->rating;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param int $eventId
     *
     * @return bool
     * @throws \Throwable
     */
    public function deleteApplaud(int $eventId): bool
    {
        return \DB::transaction(
            function () use ($eventId) {
                $event = $this->findOrFail($eventId);

                abort_if(
                    ! $this->user->hasApplauds($event->id),
                    Response::HTTP_BAD_REQUEST,
                    ErrorMessages::USER_HAVE_NO_APPLAUDS
                );

                return $event->applauds()->where(
                    [
                        'event_id' => $event->id,
                        'user_id'  => $this->user->id,
                    ]
                )->delete();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function attachTrailers(array $data): mixed
    {
        $event = $this->findOrFail($data['event_id'] ?? null);

        if (! array_key_exists('trailers', $data)) {
            throw new BadRequestException('Wrong input format');
        }
        //TODO::REWRITE TO SINGLE TRAILER!!!!!
        //TODO:REFACTOR FOR MULTIPLE MODELS, CODE DUPLICATE IN COLLECTIVES

        if (count($data['trailers']) > 1) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        };

        if ($event->trailers()->count() > EventTrailers::MAX_TRAILERS_COUNT) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        }

        $trailers = [];

        foreach ($data['trailers'] as $trailer) {
            $trailers[] = $trailer;
        }

        return $event->trailers()->createMany($trailers);
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return mixed
     * @throws \Throwable
     */
    public function updateTrailer(array $data, int $id): mixed
    {
        return \DB::transaction(
            function () use ($data, $id) {
                $trailer = EventTrailers::findOrFail($id);
                $trailer->update($data);

                return $trailer->fresh();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function deleteTrailer(int $id)
    {
        return EventTrailers::findOrFail($id)->delete();
    }

    /**
     * @param int $eventId
     * @param int $folderId
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachToBookmark(int $eventId, int $folderId): mixed
    {
        return \DB::transaction(
            function () use ($eventId, $folderId) {
                $event  = $this->findOrFail($eventId);
                $folder = BookmarkFolder::findOrFail($folderId);

                if ($folder->events->contains($event->id)) {
                    throw new BadRequestException(ErrorMessages::BOOKMARK_ALREADY_APPLIED);
                }

                return $folder->events()->attach(['event_id' => $event->id]);
            }
        );
    }

    /**
     * Attaching and updating person role
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachRoles(array $data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $event      = $this->findOrFail($data['event_id']);
                $entityType = '';
                $awardIds   = $data['award_ids'] ?? null;
                $eventRoles = $data['event_roles'];

                if ($event->type == Event::TYPE_EVENT && isset($data['collective_id'])) {
                    throw new BadRequestException(ErrorMessages::CANT_ATTACH_COLLECTIVE_TO_EVENT);
                }

                if ($event->type == Event::TYPE_EVENT && ! in_array($data['person_type'], EventPerson::$eventTypes)) {
                    throw new BadRequestException(ErrorMessages::INVALID_EVENT_PERSON_TYPE);
                }

                if ($event->type == Event::TYPE_SHOW && ! in_array($data['person_type'], EventPerson::$showTypes)) {
                    throw new BadRequestException(ErrorMessages::INVALID_SHOW_PERSON_TYPE);
                }

                if (isset($data['collective_id'])) {
                    $personOrCollective = Collective::findOrFail($data['collective_id']);
                    $entityType         = 'collective';
                } elseif (isset($data['person_id'])) {
                    $personOrCollective = Persons::findOrFail($data['person_id']);
                    $entityType         = 'persons';
                }

                if ($data['person_type'] !== EventPerson::RELATION_CASTS) {
                    $data['is_original'] = false;
                }

                if ($data['person_type'] !== EventPerson::RELATION_JURY && ! is_null($awardIds)) {
                    throw new BadRequestException(ErrorMessages::AWARDS_ONLY_FOR_JURY);
                }

                $insertData = [
                    'event_id'          => $event->id,
                    $entityType . '_id' => $personOrCollective->id,
                    'field_name'        => $data['person_type'],
                    'is_future'         => $data['is_future'] ?? false,
                    'is_original'       => $data['is_original'] ?? false,
                ];

                $searchData = [
                    'event_id'          => $event->id,
                    $entityType . '_id' => $personOrCollective->id,
                    'field_name'        => $data['person_type'],
                ];

                $eventPerson = EventPerson::updateOrCreate($searchData, $insertData);

                if (! is_null($awardIds) && $data['person_type'] == EventPerson::RELATION_JURY) {
                    $eventPerson->awards()->detach();
                    $eventPerson->awards()->attach($awardIds);
                }

                EventPersonRole::where('event_person_id', '=', $eventPerson->id)?->delete();

                foreach ($eventRoles as $name) {
                    EventPersonRole::create(
                        [
                            'name'            => $name,
                            'event_person_id' => $eventPerson->id,
                        ]
                    );
                }

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Detach person from event
     *
     * @param int $eventPersonId
     *
     * @throws \Throwable
     */
    public function detachRole(int $eventPersonId)
    {
        \DB::transaction(
            function () use ($eventPersonId) {
                $relation = EventPerson::findOrFail($eventPersonId);
                EventPersonRole::where('event_person_id', '=', $relation->id)->delete();
                $relation->delete();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Attaching and updating venue for event/show
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachVenue(array $data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $event = $this->findOrFail($data['event_id']);

                $venue = Venue::findOrFail($data['venue_id']);

                $response = EventVenue::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'venue_id' => $venue->id,
                    ],
                    [
                        'event_id'    => $event->id,
                        'venue_id'    => $venue->id,
                        'start_year'  => $data['start_year'] ?? null,
                        'start_month' => $data['start_month'] ?? null,
                        'start_day'   => $data['start_day'] ?? null,
                        'end_year'    => $data['end_year'] ?? null,
                        'end_month'   => $data['end_month'] ?? null,
                        'end_day'     => $data['end_day'] ?? null,
                    ]
                );

                return $response;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Detach venue from event/show
     *
     * @param int $eventId
     * @param int $venueId
     *
     * @throws \Throwable
     */
    public function detachVenue(int $eventId, int $venueId)
    {
        \DB::transaction(
            function () use ($eventId, $venueId) {
                $event = Event::findOrFail($eventId);
                $event->venue()->detach($venueId);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function getImages(int $eventId, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($eventId)->images()->paginate($paginate);
    }

    public function getPosters(int $eventId, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($eventId)->posters()->paginate($paginate);
    }

    public function getVideos(int $eventId, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($eventId)->videos()->paginate($paginate);
    }

    /**
     * @param array $data
     *
     * @throws \Throwable
     */
    public function uploadMedia(array $data)
    {
        \DB::transaction(
            function () use ($data) {
                $event = $this->findOrFail($data['event_id']);

                if ($data['type'] == Media::TYPE_IMAGES || $data['type'] == Media::TYPE_POSTERS) {
                    $imageName = Str::slug($event->title) . Str::random(20) .
                        time() . '.' . $data['file']->getClientOriginalExtension();

                    $this->uploadOne($data['file'], Event::MEDIA_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $imageName);

                    unset($data['file']);

                    $data['url'] = Event::MEDIA_FOLDER . $imageName;
                    $media       = Media::create($data);

                    if ($data['type'] == Media::TYPE_POSTERS) {
                        $event->posters()->attach($media);
                    } else {
                        $event->images()->attach($media);
                    }
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = Media::create($data);

                    $event->videos()->attach($media);
                }

                return $event->fresh();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Throwable
     */
    public function deleteMedia(array $data)
    {
        \DB::transaction(
            function () use ($data) {
                $event = $this->findOrFail($data['event_id']);

                Media::$withoutUrl = true;

                if ($data['type'] == Media::TYPE_IMAGES) {
                    $media     = $event->images()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if ($data['type'] == Media::TYPE_POSTERS) {
                    $media     = $event->posters()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = $event->videos()->where('media_id', '=', $data['media_id']);
                }

                if ($media->exists()) {
                    $media->delete();
                    $media->detach($data['media_id']);
                } else {
                    throw new BadRequestException('invalid media id');
                }

                if ($data['type'] == Media::TYPE_POSTERS || $data['type'] == Media::TYPE_IMAGES) {
                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($imagePath);
                }

                Media::$withoutUrl = false;

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
