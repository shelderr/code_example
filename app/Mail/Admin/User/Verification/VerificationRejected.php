<?php

namespace App\Mail\Admin\User\Verification;

use App\Jobs\QueuesNames;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationRejected extends Mailable
{
    use Queueable, SerializesModels;

    protected ?User $user;

    protected int $personId;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User|null $user
     * @param int                   $personId
     */
    public function __construct(?Model $user, int $personId)
    {
        $this->user     = $user;
        $this->personId = $personId;
        $this->onQueue(QueuesNames::DEFAULT);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.users.verifications.rejected')->with(
            [
                'userName'   => $this->user->user_name,
                'personLink' => $this->getPersonLink($this->personId),
            ]
        );
    }

    private function getPersonLink(int $id): string
    {
        $prefix = config('app.domain');

        return "$prefix/persons/person/$id";
    }
}
