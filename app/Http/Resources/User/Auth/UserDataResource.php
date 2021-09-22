<?php

namespace App\Http\Resources\User\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                                => $this->id,
            'email'                             => $this->email,
            'user_name'                         => $this->user_name,
            "last_login"                        => $this->last_login,
            "last_activity"                     => $this->last_activity,
            'photo'                             => $this->photo,
            'multiSizeImages'                   => $this->MultiSizeImages,
            'system_notifications_subscription' => $this->system_notifications_subscription,
            'news_subscription'                 => $this->news_subscription,
            'personality_link_status'           => $this->personality_link_status,
            'password_exists'                     => $this->password_exists,
            // 'kyc_verification'  => $this->kyc->status ?? false,
        ];
    }
}
