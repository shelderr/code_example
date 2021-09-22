<?php

namespace App\Services\General;

use App\Enums\BaseAppEnum;
use App\Mail\Feedback\SendFeedbackMail;
use App\Models\Collective;
use App\Models\Events\Event;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Persons;
use App\Models\Venue;
use App\Repositories\General\FeedbackRepository;
use App\Traits\UploadTrait;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FeedbackService extends FeedbackRepository
{
    use UploadTrait;

    public function sendFeedback(array $data)
    {
        return \DB::transaction(
            function () use ($data) {
                $data['user_id'] = auth()->user()->id;

                $entity   = null;
                $feedback = Feedback::create($data);

                if (isset($data['target_id']) && isset($data['target_type'])) {
                    $entity = $this->feedbackEntity($data['target_id'], $data);
                    $type   = $data['target_type'];

                    unset($data['target_id'], $data['target_type']);

                    $entity->feedback()->save($feedback);
                }

                if ($data['type'] == Feedback::TYPE_INAPPROPRIATE_CONTENT || $data['type'] == Feedback::TYPE_COPYRIGHT) {
                    $entity->blockSwitch();
                }

                if (isset($data['images'])) {
                    foreach ($data['images'] as $image) {
                        $this->saveImage($feedback, $image);
                    }
                }

                if (isset($data['links'])) {
                    $links = [];

                    foreach ($data['links'] as $link) {
                        $links[] = ['feedback_id' => $feedback->id, 'link' => $link];
                    }

                    $feedback->links()->createMany($links);
                }

                $mailTo = config('mail.feedback_to.address');

                \Mail::to($mailTo)->queue(new SendFeedbackMail($feedback, $entity, $type ?? null));

                return $feedback->refresh();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    private function saveImage(Feedback $feedback, UploadedFile $file)
    {
        $imageName = Str::slug(
            $feedback->subject . Str::random(20) .
                time() . '.' . $file
        ) . '.' . $file->getClientOriginalExtension();

        $this->uploadOne($file, Feedback::MEDIA_FOLDER, BaseAppEnum::DEFAULT_DRIVER, $imageName);

        $data['type'] = Media::TYPE_FEEDBACK_IMAGE;
        $data['url']  = Feedback::MEDIA_FOLDER . $imageName;
        $media        = Media::create($data);

        $feedback->images()->attach($media);
    }

    /**
     * @param int $id
     * @param     $data
     *
     * @return mixed
     */
    private function feedbackEntity(int $id, $data): mixed
    {
        $entity = null;

        $isExistType = in_array($data['target_type'], Feedback::$entityTypes);

        abort_if(! $isExistType, Response::HTTP_BAD_REQUEST, 'invalid target type');

        if ($data['target_type'] == Feedback::PERSON_ENTITY) {
            $entity = Persons::findOrFail($data['target_id']);
        }

        if ($data['target_type'] == Feedback::VENUE_ENTITY) {
            $entity = Venue::findOrFail($data['target_id']);
        }

        if ($data['target_type'] == Feedback::EVENT_ENTITY) {
            $entity = Event::where('type', '=', Event::TYPE_EVENT)->findOrFail($data['target_id']);
        }

        if ($data['target_type'] == Feedback::SHOW_ENTITY) {
            $entity = Event::where('type', '=', Event::TYPE_SHOW)->findOrFail($data['target_id']);
        }

        if ($data['target_type'] == Feedback::COLLECTIVE_ENTITY) {
            $entity = Collective::findOrFail($data['target_id']);
        }

        return $entity;
    }
}
