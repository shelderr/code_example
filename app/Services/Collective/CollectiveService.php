<?php

namespace App\Services\Collective;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Models\Collective;
use App\Models\Collectives\CollectivePerson;
use App\Models\Country;
use App\Models\Collectives\CollectiveTrailers;
use App\Models\Media;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Repositories\CollectiveRepository;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CollectiveService extends CollectiveRepository
{
    use UploadTrait;

    public function create(array $data): Model
    {
        return \DB::transaction(
            function () use ($data) {
                if (isset($data['image'])) {
                    $photo = Str::slug($data['name']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes($data['image'], $this->model()::IMAGE_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $photo);

                    $data['image'] = $this->model()::IMAGE_FOLDER . $photo;
                }

                $collective = parent::create($data);

                if (isset($data['countries'])) {
                    $collective->countries()->attach(
                        $data['countries'],
                        ['country_type' => Country::COLLECTIVE_COUNTRY_TYPE]
                    );
                }

                return $collective;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param array  $data
     * @param int    $id
     * @param string $attribute
     *
     * @return \Model|null
     */
    public function update(array $data, int $id, $attribute = 'id')
    {
        $collective   = $this->findOrFail($id);
        $countriesIds = $data['countries'] ?? null;

        unset($data['countries'], $data['roles']);

        if (array_key_exists('image', $data)) {
            $name = Str::slug($data['name']) . Str::random(20) .
                time() . '.' . $data['image']->getClientOriginalExtension();

            $this->uploadMultipleSizes($data['image'], $this->model()::IMAGE_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $name);

            $data['image'] = $this->model()::IMAGE_FOLDER . $name;

            get_class_vars($this->model())['withoutUrl'] = true;

            \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($collective->image);
            if (! is_null($collective->image)) {
                foreach ($collective->multiSizeImages as $image) {
                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                }
            }

            get_class_vars($this->model())['withoutUrl'] = false;
        }

        $collective->update($data);

        $collective->countries()->detach();

        if (! is_null($countriesIds)) {
            $collective->countries()->attach($countriesIds, ['country_type' => Country::COLLECTIVE_COUNTRY_TYPE]);
        }

        return $collective->fresh();
    }

    /**
     * Attach person with role
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachPerson(array $data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $personAttempted = CollectivePerson::where('collective_id', '=', $data['collective_id'])
                    ->where('person_id', '=', $data['person_id']);

                if ($personAttempted->exists()) {
                    throw new BadRequestException(ErrorMessages::PERSON_ALREADY_ATTEMPTED);
                }

                $collectivePerson = CollectivePerson::create($data);

                return $collectivePerson->with('person')->get();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Updating attached person to the collective
     *
     * @param $data
     *
     * @return mixed
     * @throws \Throwable
     */
    public function editPerson($data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $collectivePerson = CollectivePerson::findOrFail($data['collective_person_id']);

                unset($data['collective_person_id']);

                $collectivePerson->update($data);

                return $collectivePerson->with('person')->get();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @throws \Throwable
     */
    public function deletePerson($data): mixed
    {
        return \DB::transaction(
            function () use ($data) {
                $person = CollectivePerson::findOrFail($data['collective_person_id']);

                return $person->delete();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Attach to user bookmarks
     *
     * @param int $collectiveId
     * @param int $folderId
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachToBookmark(int $collectiveId, int $folderId): mixed
    {
        return \DB::transaction(
            function () use ($collectiveId, $folderId) {
                $collective  = $this->findOrFail($collectiveId);
                $folder = BookmarkFolder::findOrFail($folderId);

                if ($folder->collectives->contains($collective->id)) {
                    throw new BadRequestException(ErrorMessages::BOOKMARK_ALREADY_APPLIED);
                }

                return $folder->collectives()->attach(['collective_id' => $collective->id]);
            }
        );
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
                $collective = $this->findOrFail($data['collective_id']);

                if ($data['type'] == Media::TYPE_IMAGES || $data['type'] == Media::TYPE_HEADSHOTS) {
                    $imageName = Str::slug($collective->name) . Str::random(20) .
                        time() . '.' . $data['file']->getClientOriginalExtension();

                    $this->uploadOne($data['file'], Collective::MEDIA_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $imageName);

                    unset($data['file']);

                    $data['url'] = Collective::MEDIA_FOLDER . $imageName;
                    $media       = Media::create($data);

                    if ($data['type'] == Media::TYPE_IMAGES) {
                        $collective->images()->attach($media);
                    } else {
                        $collective->headshots()->attach($media);
                    }
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = Media::create($data);

                    $collective->videos()->attach($media);
                }

                return $collective->fresh();
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
    public function deleteMedia(array $data): bool
    {
        return \DB::transaction(
            function () use ($data) {
                $collective = $this->findOrFail($data['collective_id']);

                Media::$withoutUrl = true;

                $media = null;

                if ($data['type'] == Media::TYPE_IMAGES) {
                    $media     = $collective->images()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if ($data['type'] == Media::TYPE_VIDEOS) {
                    $media = $collective->videos()->where('media_id', '=', $data['media_id']);
                }

                if ($data['type'] == Media::TYPE_HEADSHOTS) {
                    $media     = $collective->headshots()->where('media_id', '=', $data['media_id']);
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
        $collective = $this->findOrFail($data['collective_id'] ?? null);

        if (! array_key_exists('trailers', $data)) {
            throw new BadRequestException('Wrong input format');
        }

        //TODO::REWRITE TO SINGLE TRAILER!!!!!
        //TODO:REFACTOR FOR MULTIPLE MODELS, CODE DUPLICATE IN Events

        if (count($data['trailers']) > 1) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        };

        if ($collective->trailers()->count() >= CollectiveTrailers::MAX_TRAILERS_COUNT) {
            throw new BadRequestException(ErrorMessages::TRAILERS_EXCEEDED, Response::HTTP_NOT_ACCEPTABLE);
        }

        $trailers = [];

        foreach ($data['trailers'] as $trailer) {
            $trailers[] = $trailer;
        }

        return $collective->trailers()->createMany($trailers);
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
                $trailer = CollectiveTrailers::findOrFail($id);
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
        return CollectiveTrailers::findOrFail($id)->delete();
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
