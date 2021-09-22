<?php

namespace App\Services\Venue;

use App\Enums\BaseAppEnum;
use App\Exceptions\Http\BadRequestException;
use App\Models\Media;
use App\Models\Venue;
use Illuminate\Support\Str;

class VenueMediaService extends VenueService
{
    /**
     * @param array $data
     *
     * @throws \Throwable
     */
    public function uploadMedia(array $data)
    {
        \DB::transaction(
            function () use ($data) {
                $venue = $this->findOrFail($data['venue_id']);

                if ($data['type'] == Media::TYPE_IMAGES) {
                    $imageName = Str::slug($venue->title) . Str::random(20) .
                        time() . '.' . $data['file']->getClientOriginalExtension();

                    $this->uploadOne($data['file'], Venue::MEDIA_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $imageName);

                    unset($data['file']);

                    $data['url'] = Venue::MEDIA_FOLDER . $imageName;
                    $media       = Media::create($data);

                    if ($data['type'] == Media::TYPE_IMAGES) {
                        $venue->images()->attach($media);
                    }
                }

                return $venue->fresh();
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
                $venue = $this->findOrFail($data['venue_id']);

                Media::$withoutUrl = true;

                $media = null;

                if ($data['type'] == Media::TYPE_IMAGES) {
                    $media     = $venue->images()->where('media_id', '=', $data['media_id']);
                    $imagePath = $media->first()->url;
                }

                if (! is_null($media) && $media->exists()) {
                    $media->delete();
                    $media->detach($data['media_id']);
                } else {
                    throw new BadRequestException('invalid media id');
                }

                if ($data['type'] == Media::TYPE_IMAGES) {
                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($imagePath);
                }

                Media::$withoutUrl = false;

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
