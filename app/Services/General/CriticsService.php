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
use App\Repositories\CriticsRepository;
use App\Repositories\DetailsRepository;
use App\Repositories\General\FeedbackRepository;
use App\Repositories\LinksRepository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CriticsService extends CriticsRepository
{
    use UploadTrait;

    public function attachCritics(array $data)
    {
        return \DB::transaction(
            function () use ($data) {
                $entity          = $this->criticEntity($data);
                $data['user_id'] = auth()->guard(BaseAppGuards::USER)->user()?->id;
                $links           = $this->create($data);

                $entity->critics()->attach($links);

                return $links;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
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
