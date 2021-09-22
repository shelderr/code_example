<?php

namespace App\Services;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Base\AuthorizeService;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;

class UserService extends AuthorizeService
{
    use UploadTrait;

    /**
     * UserService constructor.
     *
     * @param Application $application
     *
     * @throws \App\Exceptions\Application\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $application)
    {
        parent::__construct(new UserRepository($application));
    }

    /**
     * @return string
     */
    public function guard(): string
    {
        return BaseAppGuards::USER;
    }

    /**
     * @param array $data
     *
     * @return array|null
     * @throws \Throwable
     */
    public function register(array $data): ?array
    {
        return parent::register(
            [
                'user_name'         => $data['user_name'],
                'password'          => $data['password'],
                'email'             => $data['email'],
                'google_id'         => $data['google_id'] ?? null,
                'news_subscription' => $data['news_subscription'] ?? false,
            ]
        );
    }

    /**
     * @param string $searchField
     * @param string $filedValue
     *
     * @return array
     * @throws \ReflectionException
     */
    public function isEmailConfirmed(string $searchField, string $filedValue): array
    {
        $user = $this->model->findByOrFail($searchField, $filedValue);

        return ['confirmed' => $user->email_confirmed ?? false, 'email' => $user->email ?? null];
    }

    /**
     * @param array $data
     * @param int   $id
     *
     * @return void
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function changePassword(array $data, int $id): void
    {
        if (! Hash::check($data['current_password'], auth()->user()->password)) {
            throw new BadRequestException('Old password is incorrect');
        }

        if (strcmp($data['current_password'], $data['new_password']) === 0) {
            throw new BadRequestException('New password can`t be the same as current');
        }

        $user           = $this->model->findOrFail($id);
        $user->password = $data['new_password'];
        $user->save();
    }

    /**
     * @param $phone
     * @param $id
     *
     * @throws \Exception
     */
    public function changePhone($phone, $id)
    {
        $user = $this->model->findOrFail($id);

        if ($user->authy2fa_enabled) {
            throw new  BadRequestException(ErrorMessages::TWO_FA_AUTH_ENABLED, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user->phone = $phone;
        $user->save();
    }

    public function changeUsername(User $user, string $username): User
    {
        $user->user_name = $username;
        $user->save();

        return $user->fresh();
    }

    /**
     * @param string $email
     *
     * @throws \App\Exceptions\Application\ApplicationException
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \ReflectionException
     */
    public function resendEmail(string $email): void
    {
        parent::resendEmail($email);
    }

    /**
     * @param $user
     * @param $device
     *
     * @return mixed
     */
    public function userActivity($request, $user)
    {
        return $user->activities()->create(
            [
                'user_id'    => $user->id,
                'last_login' => Carbon::now(),
                'device'     => $request->header('User-Agent'),
            ]
        );
    }

    /**
     * @param $request
     *
     * @return object
     * @throws \Throwable
     */
    public function editInfo($request): object
    {
        return DB::transaction(
            function () use ($request) {
                $user = auth()->guard(BaseAppGuards::USER)->user();
                $data = $request->validated();

                if ($request->has('photo')) {
                    User::$withoutUrl = true;

                    if ($user->photo) {
                        Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($user->photo);

                        foreach ($user->multiSizeImages as $photo) {
                            \Storage::disk(BaseAppEnum::DEFAULT_DRIVER)->delete($photo);
                        }
                    }

                    User::$withoutUrl = false;
                    $image            = $request->file('photo');
                    $name             = 'photo' . '_' . time();
                    $folder           = '/uploads/users/' . $user->uuid . '/';
                    $filepath         = $folder . $name . '.' . $image->getClientOriginalExtension();

                    $this->uploadMultipleSizes(
                        $image,
                        $folder,
                        BaseAppEnum::DEFAULT_DRIVER,
                        $name . '.' . $image->getClientOriginalExtension()
                    );
                    $data['photo'] = $filepath;
                }

                $user->update($data);

                return $user;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    /**
     * @param \App\Models\User $user
     * @param string           $token
     *
     * @return mixed
     * @throws \Throwable
     */
    public function deleteAccount(User $user, string $token)
    {
        return DB::transaction(
            function () use ($user, $token) {
                $user->is_deleted = true;
                $user->active = false;
                $user->save();

                Auth::setToken($token)->invalidate();

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
