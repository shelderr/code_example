<?php

namespace App\Services\User;

use App\Enums\BaseAppEnum;
use App\Exceptions\Http\BadRequestException;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Support\Facades\DB;
use Spatie\Newsletter\Newsletter;

class SubscribeService
{
    public Newsletter $mailchimp;

    private ?User $user;

    /**
     * SubscribeService constructor.
     *
     * @param \Spatie\Newsletter\Newsletter $mailChimp
     */
    public function __construct(Newsletter $mailChimp)
    {
        $this->mailchimp = $mailChimp;
        $this->user      = auth()->guard(BaseAppGuards::USER)->user();
    }

    public function subscribeNewsletter(bool $subscribed)
    {
        return \DB::transaction(
            function () use ($subscribed) {
                $subscriptionStatus = $this->user->news_subscription;
                $userEmail = (string) $this->user->email;
                $userSubscribed = $this->mailchimp->isSubscribed($userEmail);

                if ($subscriptionStatus == true && $subscribed == true && $this->mailchimp->isSubscribed($userEmail)) {
                    throw  new BadRequestException('Already have subscription');
                }

                if ($subscribed == true && $userSubscribed == false) {
                    $this->mailchimp->subscribeOrUpdate($userEmail);
                    $this->user->update(['news_subscription' => true]);
                }

                if ($subscriptionStatus == true && $subscribed == false && $userSubscribed) {
                    $this->mailchimp->unsubscribe($this->user->email);
                    $this->user->update(['news_subscription' => false]);
                }

                return UserDataResource::make($this->user->fresh());
            }
        );
    }

    /**
     * @return \App\Http\Resources\User\Auth\UserDataResource
     * @throws \Throwable
     */
    public function systemNotificationsSwitch(): UserDataResource
    {
        DB::transaction(
            function () {
                $this->user->system_notifications_subscription = $this->user->system_notifications_subscription == false ? true : false;
                $this->user->save();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );

        return UserDataResource::make($this->user->fresh());
    }
}
