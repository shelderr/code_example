<?php

namespace App\Services\Venue;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Models\Country;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Repositories\VenueRepository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VenueService extends VenueRepository
{
    use UploadTrait;

    public function index(int $paginate): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getAll($paginate);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Throwable
     */
    public function create(array $data): Model
    {
        return \DB::transaction(
            function () use ($data) {
                $data    = $this->startDateSaveModifier($data);
                $country = null;

                if (isset($data['country_id'])) {
                    $country = Country::find($data['country_id']);
                }

                if (is_null($country) && array_keys_exists(['city', 'street_address', 'coordinates'], $data)) {
                    throw new BadRequestException(ErrorMessages::ONLY_COUNTRY_ALLOWED);
                }

                if (isset($data['image'])) {
                    $photo = Str::slug($data['name']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'], $this->model()::IMAGE_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $photo
                    );

                    $data['image'] = $this->model()::IMAGE_FOLDER . $photo;
                }

                return parent::create($data);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function startDateSaveModifier(array $data): array
    {
        if (isset($data['opening_year'])) {
            $startDate = makeTrueDate(
                $data['opening_year'],
                $data['opening_month'] ?? null,
                $data['opening_day'] ?? null
            );

            foreach ($startDate as $key => $date) {
                if (is_null($date)) {
                    unset($data["opening_$key"]);
                } else {
                    $data["opening_$key"] = $date;
                }
            }
        }

        return $data;
    }

    /**
     * @throws \Throwable
     */
    public function update(array $data, int $id, string $attribute = "id"): mixed
    {
        return \DB::transaction(
            function () use ($data, $id, $attribute) {
                $venue = $this->findOrFail($id);
                $data  = $this->openingDateUpdateModifier($data);

                if (isset($data['country_id'])) {
                    $country = Country::find($data['country_id']);

                    if (is_null($country->code) && array_keys_exists(['city', 'street_address', 'coordinates'], $data)) {
                        throw new BadRequestException(ErrorMessages::ONLY_COUNTRY_ALLOWED);
                    }
                }

                if (array_key_exists('image', $data)) {
                    $name = Str::slug($data['name']) . Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'], $this->model()::IMAGE_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $name
                    );

                    $data['image'] = $this->model()::IMAGE_FOLDER . $name;

                    get_class_vars($this->model())['withoutUrl'] = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($venue->image);

                    if (! is_null($venue->image)) {
                        foreach ($venue->multiSizeImages as $image) {
                            \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                        }
                    }

                    get_class_vars($this->model())['withoutUrl'] = false;
                }

                $venue->update($data);

                return $venue->fresh('category');
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * Modification update
     *
     * @param array $data
     *
     * @return array
     * @throws \App\Exceptions\Http\BadRequestException
     */
    private function openingDateUpdateModifier(array $data): array
    {
        if (isset($data['opening_year'])) {
            $establishedDate = makeTrueDate(
                $data['opening_year'] ?? null,
                $data['opening_month'] ?? null,
                $data['opening_day'] ?? null
            );

            foreach ($establishedDate as $key => $date) {
                $data["opening_$key"] = $date;
            }
        } else {
            unset($data['opening_year'], $data['opening_month'], $data['opening_day']);
        }

        return $data;
    }

    /**
     * Attach to user bookmarks
     *
     * @param int $venueId
     * @param int $folderId
     *
     * @return mixed
     * @throws \Throwable
     */
    public function attachToBookmark(int $venueId, int $folderId): mixed
    {
        return \DB::transaction(
            function () use ($venueId, $folderId) {
                $venue  = $this->findOrFail($venueId);
                $folder = BookmarkFolder::findOrFail($folderId);

                if ($folder->venues->contains($venue->id)) {
                    throw new BadRequestException(ErrorMessages::BOOKMARK_ALREADY_APPLIED);
                }

                return $folder->venues()->attach(['venue_id' => $venue->id]);
            }
        );
    }
}
