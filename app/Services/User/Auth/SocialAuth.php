<?php

namespace App\Services\User\Auth;

use App\Exceptions\Auth\Social\ThisEmailIsBlocked;
use App\Exceptions\Auth\Social\ThisUserIsDeleted;
use App\Exceptions\Auth\Social\UserDontHaveEmailException;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\Base\BaseAppGuards;
use App\Services\Helpers\User\Auth\BaseSocialAuthInterface;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

abstract class SocialAuth extends UserService implements BaseSocialAuthInterface
{
    private Request $request;

    abstract public function socialDriver(): string;

    abstract public function authAndLogin($socialUser, int $ttlRemember): string;

    public function __construct(Application $application, Request $request)
    {
        parent::__construct($application);
        $this->request = $request;
    }

    /**
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Auth\Social\UserDontHaveEmailException
     * @throws \App\Exceptions\Auth\Social\ThisUserIsDeleted
     */
    public function login(string $token): array
    {
        try {
            $user = Socialite::driver($this->socialDriver())->userFromToken($token);
        } catch (\Exception $exception) {
            throw new BadRequestException(
                ErrorMessages::INVALID_TOKEN,
                Response::HTTP_BAD_REQUEST
            );
        }

        if (is_null($user->email)) {
            throw new UserDontHaveEmailException();
        }

        $existingUser = $this->model->newQuery()->withTrashed()->where('email', '=', $user->email)->first();

        if ($existingUser) {
            if ($existingUser->blocked) {
                throw new ThisUserIsDeleted();
            }

            if ($existingUser->is_deleted) {
                throw new ThisUserIsDeleted();
            }


            if (! is_null($existingUser->deleted_at)) {
                throw new ThisUserIsDeleted();
            }
        }

        $ttlRemember = config('jwt.ttl');

        if ($existingUser) {
            $token = auth()->guard(BaseAppGuards::USER)->setTtl($ttlRemember)->login($existingUser);
        } else {
            $token = $this->authAndLogin($user, $ttlRemember);
        }

        $user = \Auth::user();

        $this->userActivity($this->request, $user);

        return ['token' => $token, 'user' => UserDataResource::make($user)];
    }
}
