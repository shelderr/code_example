<?php

namespace App\Services\User\Auth;

use App\Enums\BaseAppEnum;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Support\Facades\DB;

class GoogleAuthService extends SocialAuth
{
    public function socialDriver(): string
    {
        return self::DRIVER_GOOGLE;
    }

    /**
     * @throws \Throwable
     */
    public function authAndLogin($socialUser, int $ttlRemember): string
    {
        return DB::transaction(
            function () use ($socialUser, $ttlRemember) {
                $newUser                  = new User;
                $newUser->email           = $socialUser->user['email'];
                $newUser->google_id       = $socialUser->id;
                $newUser->email_confirmed = true;
                $newUser->save();

                return auth()->guard(BaseAppGuards::USER)->setTtl($ttlRemember)->login($newUser);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
