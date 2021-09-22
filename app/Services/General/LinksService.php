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
use App\Repositories\LinksRepository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class LinksService extends LinksRepository
{
    use UploadTrait;

    public function attachLink(array $data)
    {
        return \DB::transaction(
            function () use ($data) {
                $entity = $this->linkEntity($data);
                $data['user_id'] = auth()->guard(BaseAppGuards::USER)->user()?->id;
                $links = $this->create($data);

                $entity->links()->attach($links);

                return $links;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function update(array $data, int $id, string $attribute = "id")
    {
        return \DB::transaction(
            function () use ($data, $id, $attribute) {
                $detail = $this->findOrFail($id);
                $data['user_id'] = auth()->guard(BaseAppGuards::USER)->user()?->id;

                parent::update($data, $id, $attribute);

                return $detail->fresh();
            }
        );
    }

    public function delete(int $id): bool
    {
        return \DB::transaction(
            function () use ($id) {
                return parent::delete($id);
            }
        );
    }
}
