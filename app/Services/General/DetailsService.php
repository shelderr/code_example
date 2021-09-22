<?php

namespace App\Services\General;

use App\Enums\BaseAppEnum;
use App\Mail\Feedback\SendFeedbackMail;
use App\Models\Collective;
use App\Models\Details;
use App\Models\Events\Event;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Persons;
use App\Models\Venue;
use App\Repositories\DetailsRepository;
use App\Repositories\General\FeedbackRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DetailsService extends DetailsRepository
{
    use UploadTrait;

    public function attachDetail(array $data)
    {
        return \DB::transaction(
            function () use ($data) {
                $entity = $this->detailsEntity($data);

                if (array_key_exists('image', $data)) {
                    $name = Str::random(20) . time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'],
                        $this->model()::IMAGE_FOLDER,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $name
                    );

                    $data['image'] = $this->model()::IMAGE_FOLDER . $name;
                }

                $details = $this->create($data);

                $entity->details()->attach($details);

                return $details;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function update(array $data, int $id, string $attribute = "id")
    {
        return \DB::transaction(
            function () use ($data, $id, $attribute) {
                $details = $this->findOrFail($id);

                if (array_key_exists('image', $data)) {
                    $name = Str::random(20) .
                        time() . '.' . $data['image']->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $data['image'],
                        $this->model()::IMAGE_FOLDER,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $name
                    );

                    $data['image'] = $this->model()::IMAGE_FOLDER . $name;

                    Details::$withoutUrl = true;

                    \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($details->image);

                    if (! is_null($details->image)) {
                        foreach ($details->multiSizeImages as $image) {
                            \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                        }
                    }

                    Details::$withoutUrl = false;
                }

                parent::update($data, $id, $attribute);

                return $details;
            }
        );
    }

    public function delete(int $id): bool
    {
        return \DB::transaction(
            function () use ($id) {
                $details = $this->findOrFail($id);

                parent::delete($id);

                Details::$withoutUrl = true;

                \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($details->image);

                if (! is_null($details->image)) {
                    foreach ($details->multiSizeImages as $image) {
                        \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($image);
                    }
                }

                Details::$withoutUrl = false;

                return true;
            }
        );
    }
}
