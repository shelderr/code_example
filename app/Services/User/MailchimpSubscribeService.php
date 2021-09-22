<?php

namespace App\Services\User;

use App\Exceptions\Http\BadRequestException;
use Illuminate\Http\Response;
use Spatie\Newsletter\Newsletter;

class MailchimpSubscribeService
{
    public Newsletter $mailchimp;

    /**
     * SubscribeService constructor.
     *
     * @param \Spatie\Newsletter\Newsletter $mailChimp
     */
    public function __construct(Newsletter $mailChimp)
    {
        $this->mailchimp = $mailChimp;
    }

    /**
     * @param $email
     *
     * @return array|bool
     */
    public function subscribe($email)
    {
        if ($this->mailchimp->isSubscribed($email)) {
            return true;
        }

        return $this->mailchimp->subscribe($email);
    }
}
