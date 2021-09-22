<?php

namespace App\Mail\Feedback;

use App\Jobs\QueuesNames;
use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    private $feedback;

    private $entity;

    private string $type;

    private string $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback, $entity, ?string $type)
    {
        $this->subject  = __('email_subjects.user_feedback');
        $this->queue    = QueuesNames::DEFAULT;
        $this->feedback = $feedback;
        $this->entity   = $entity;
        $this->type     = $type ?? 'no type';
        $this->title    = $this->feedback->subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.feedback.feedback')
            ->with(
                [
                    'feedbackType' => $this->feedback->type,
                    'userName'     => $this->feedback->user->user_name,
                    'title'        => $this->title,
                    'msg'          => $this->feedback->message,
                    'url'          => is_null($this->entity) ? null :$this->entityUrl($this->entity->id)[$this->type],
                    'images'       => $this->feedback->images,
                    'links'        => $this->feedback->links,
                ]
            )
            ->subject($this->subject);
    }

    private function entityUrl(int $id): array
    {
        $prefix = config('app.domain');

        return [
            Feedback::COLLECTIVE_ENTITY => "$prefix/collectives/collective/$id",
            Feedback::EVENT_ENTITY      => "$prefix/events/event/$id",
            Feedback::SHOW_ENTITY       => "$prefix/shows/show/$id",
            Feedback::VENUE_ENTITY      => "$prefix/venues/venue/$id",
            Feedback::PERSON_ENTITY     => "$prefix/persons/person/$id",
        ];
    }
}
