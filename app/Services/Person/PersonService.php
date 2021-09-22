<?php

namespace App\Services\Person;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\AccessDenyException;
use App\Exceptions\Http\BadRequestException;
use App\Models\Country;
use App\Models\Events\Event;
use App\Models\Media;
use App\Models\Persons;
use App\Models\Roles;
use App\Models\User;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Repositories\Event\EventRepository;
use App\Repositories\PersonRepository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Guizoxxv\LaravelMultiSizeImage\MultiSizeImage;
use Illuminate\Contracts\Foundation\Application;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PersonService extends PersonRepository
{
    use UploadTrait;

    private ?User $user;

    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
        $this->user = auth()->guard(BaseAppGuards::USER)->user();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $data): Model
    {
        return \DB::transaction(
            function () use ($data) {
                $data = $this->birthDeathDateSaveModifier($data);

                if (isset($data['image'])) {
                    $photo = Str::slug($data['name']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'],
                        $this->model()::PHOTO_FOLDER,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $photo
                    );

                    $data['image'] = $this->model()::PHOTO_FOLDER . $photo;
                }

                $person = parent::create($data);

                if (isset($data['countries'])) {
                    $person->countries()->attach($data['countries'], ['country_type' => Country::PERSON_COUNTRY_TYPE]);
                }

                if (isset($data['roles'])) {
                    $person->roles()->sync($data['roles']);
                }

                return $person->fresh();
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
    private function birthDeathDateSaveModifier(array $data): array
    {
        $birthDate = makeTrueDate(
            $data['birth_year'] ?? null,
            $data['birth_month'] ?? null,
            $data['birth_day'] ?? null
        );

        foreach ($birthDate as $key => $date) {
            if (is_null($date)) {
                unset($data["birth_$key"]);
            } else {
                $data["birth_$key"] = $date;
            }
        }

        if (isset($data['death_year']) || isset($date['death_month']) || isset($data['death_day'])) {
            $deathDate = makeTrueDate(
                $data['death_year'] ?? null,
                $data['death_month'] ?? null,
                $data['death_day'] ?? null
            );

            if ($deathDate <= $birthDate) {
                throw new BadRequestException('Death date cant be less then birth date');
            }

            foreach ($deathDate as $key => $date) {
                if (is_null($date)) {
                    unset($data["death_$key"]);
                } else {
                    $data["death_$key"] = $date;
                }
            }

            $carbonDeathDate = Carbon::createFromDate(
                $data['death_year'],
                $data['death_month'] ?? null,
                $data['death_day'] ?? null
            );

            if ($carbonDeathDate->diffInDays(Carbon::now(), false) < 0) {
                throw new BadRequestException('death date cant be greater than now');
            }
        }

        if (isset($data['birth_year'])) {
            $carbonBirthDay = Carbon::createFromDate(
                $data['birth_year'],
                $data['birth_month'] ?? null,
                $data['birth_day'] ?? null
            );

            if ($carbonBirthDay->diffInDays(Carbon::now(), false) < 0) {
                throw new BadRequestException('birth cant be greater than now');
            }
        }

        return $data;
    }

    /**
     * @param array  $data
     * @param int    $id
     * @param string $attribute
     *
     * @return bool|int|mixed
     * @throws \Throwable
     */
    public function update(array $data, int $id, string $attribute = "id"): mixed
    {
        return \DB::transaction(
            function () use ($data, $id, $attribute) {
                $person       = $this->findOrFail($id);
                $countriesIds = $data['countries'] ?? null;
                $roles        = $data['roles'] ?? [];

                unset($data['countries'], $data['roles']);

                $data = $this->birthDeathDateUpdateModifier($data, $person);

                if (array_key_exists('image', $data)) {
                    $name = Str::slug($data['name']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'],
                        $this->model()::PHOTO_FOLDER,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $name
                    );

                    $data['image'] = $this->model()::PHOTO_FOLDER . $name;

                    Persons::$withoutUrl = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($person->image);

                    if (! is_null($person->image)) {
                        foreach ($person->multiSizeImages as $image) {
                            \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                        }
                    }

                    Persons::$withoutUrl = false;
                }

                $person->update($data);

                $person->countries()->detach();

                if (! is_null($countriesIds)) {
                    $person->countries()->attach($countriesIds, ['country_type' => Country::PERSON_COUNTRY_TYPE]);
                }

                $person->roles()->sync($roles);

                return $person->fresh();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**sorry
     *
     * @param array               $data
     * @param \App\Models\Persons $person
     *
     * @return array
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function birthDeathDateUpdateModifier(array $data, Persons $person): array
    {
        $birthDate = makeTrueDate(
            $data['birth_year'] ?? null,
            $data['birth_month'] ?? null,
            $data['birth_day'] ?? null
        );

        foreach ($birthDate as $key => $date) {
            $data["birth_$key"] = $date;
        }

        $deathDate = makeTrueDate(
            $data['death_year'] ?? null,
            $data['death_month'] ?? null,
            $data['death_day'] ?? null
        );

        if (is_null($deathDate['year']) && is_null($deathDate['month']) && is_null($deathDate['day'])) {
            $deathDateExists = false;
        } else {
            $deathDateExists = true;
        }

        if (is_null($birthDate['year']) && is_null($birthDate['month']) && is_null($birthDate['day'])) {
            $birthDateExists = false;
        } else {
            $birthDateExists = true;
        }

        if ($person->is_deceased && ! isset($data['is_deceased'])) {
            $deathDate = makeTrueDate(
                $person->death_year,
                $person->death_month ?? null,
                $person->death_day ?? null
            );
        }

        $validationDeathDate = Carbon::createFromDate($deathDate['year'], $deathDate['month'], $deathDate['day']);
        $validationBirthDate = Carbon::createFromDate($birthDate['year'], $birthDate['month'], $birthDate['day']);
        $validationDatesDiff = $validationBirthDate->diffInDays($validationDeathDate, false);

        if ($deathDateExists && $birthDateExists == false) {
            if ($validationDeathDate->diffInDays(Carbon::now(), false) < 0) {
                throw new BadRequestException('Death date cant be greater than now');
            }
        }

        if ($deathDateExists && $birthDateExists) {
            if ($validationDatesDiff <= 0) {
                throw new BadRequestException('Death date cant be less than birth date');
            }
        }

        if (isset($data['birth_year'])) {
            $carbonBirthDay = Carbon::createFromDate(
                $data['birth_year'],
                $data['birth_month'] ?? null,
                $data['birth_day'] ?? null
            );

            if ($carbonBirthDay->diffInDays(Carbon::now(), false) < 0) {
                throw new BadRequestException('birth cant be greater than now');
            }
        }

        foreach ($deathDate as $key => $date) {
            $data["death_$key"] = $date;
        }

        return $data;
    }

    /**
     * @param int $personId
     * @param int $folderId
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachToBookmark(
        int $personId,
        int $folderId
    ): mixed {
        return \DB::transaction(
            function () use ($personId, $folderId) {
                $person = $this->findOrFail($personId);
                $folder = BookmarkFolder::findOrFail($folderId);

                if ($folder->persons->contains($person->id)) {
                    throw new BadRequestException(ErrorMessages::BOOKMARK_ALREADY_APPLIED);
                }

                return $folder->persons()->attach(['person_id' => $person->id]);
            }
        );
    }

    /**
     * @param int    $personId
     * @param string $facebook_url
     *
     * @return mixed
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function linkUserToPerson(
        int $personId,
        string $facebook_url
    ) {
        $person = $this->findOrFail($personId);

        if ($person->getLinkedUserByStatus(User::LINK_STATUS_ACCEPTED)->exists()) {
            throw new BadRequestException(ErrorMessages::PERSON_ALREADY_VERIFIED);
        };

        if ($this->user->personalityLink()->withPivot('status')->first()?->pivot->status ==
            User::LINK_STATUS_ACCEPTED
        ) {
            throw new BadRequestException(ErrorMessages::USER_ALREADY_HAVE_VERIFICATION);
        }

        if ($this->user->personalityLink()->exists()) {
            throw new BadRequestException(ErrorMessages::USER_WAITING_VERIFICATION);
        }

        return $person->linkedUser()->attach(
            $personId,
            [
                'user_id'      => $this->user->id,
                'facebook_url' => $facebook_url,
                'status'       => User::LINK_STATUS_PENDING,
            ]
        );
    }

    /**
     * @param array $data
     *
     * @throws \Throwable
     */
    public function uploadMedia(array $data)
    {
       return \DB::transaction(
            function () use ($data) {
                $person = $this->findOrFail($data['person_id']);

                if ($data['type'] == Media::TYPE_HEADSHOTS) {
                    unset($data['permission']);
                }

                if ($data['type'] == Media::TYPE_IMAGES || $data['type'] == Media::TYPE_HEADSHOTS) {
                    $imageName = Str::slug($person->name) . Str::random(20) .
                        time() . '.' . $data['file']->getClientOriginalExtension();

                    $this->uploadOne($data['file'], Persons::MEDIA_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $imageName);

                    unset($data['file']);

                    $data['url'] = Persons::MEDIA_FOLDER . $imageName;
                    $media       = Media::create($data);

                    if ($data['type'] == Media::TYPE_IMAGES) {
                        $person->images()->attach($media);
                    } else {
                        $person->headshots()->attach($media);
                    }
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = Media::create($data);

                    $person->videos()->attach($media);
                }

                return $media->refresh();
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
                $person = $this->findOrFail($data['person_id']);

                Media::$withoutUrl = true;

                $media = null;

                if ($data['type'] == Media::TYPE_IMAGES) {
                    $media     = $person->images()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = $person->videos()->where('media_id', '=', $data['media_id']);
                }

                if ($data['type'] == Media::TYPE_HEADSHOTS) {
                    $media     = $person->headshots()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if (! is_null($media) && $media->exists()) {
                    $media->delete();
                    $media->detach($data['media_id']);
                } else {
                    throw new BadRequestException('invalid media id');
                }

                if ($data['type'] == Media::TYPE_IMAGES || $data['type'] == Media::TYPE_HEADSHOTS) {
                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($imagePath);
                }

                Media::$withoutUrl = false;

                return true;
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
        $person = $this->findOrFail($data['person_id'] ?? null);

        if (! array_key_exists('trailers', $data)) {
            throw new BadRequestException('Wrong input format');
        }
        //TODO::REWRITE TO SINGLE TRAILER!!!!!
        //TODO:REFACTOR FOR MULTIPLE MODELS, CODE DUPLICATE IN COLLECTIVES

        if (count($data['trailers']) > 1) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        };

        if ($person->trailers()->count() >= Persons\PersonTrailers::MAX_TRAILERS_COUNT) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        }

        $trailers = [];

        foreach ($data['trailers'] as $trailer) {
            $trailers[] = $trailer;
        }

        return $person->trailers()->createMany($trailers);
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
                $trailer = Persons\PersonTrailers::findOrFail($id);
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
        return Persons\PersonTrailers::findOrFail($id)->delete();
    }

    public function getImages(int $id, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($id)->images()->paginate($paginate);
    }

    public function getVideos(int $id, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($id)->videos()->paginate($paginate);
    }

    public function getHeadshots(int $id, int $paginate): LengthAwarePaginator
    {
        return $this->findOrFail($id)->headshots()->paginate($paginate);
    }
}
