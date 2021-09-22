<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Settings\ChangePasswordRequest;
use App\Http\Requests\User\Settings\ChangePhoneRequest;
use App\Http\Requests\User\Settings\ChangePhotoRequest;
use App\Http\Requests\User\Settings\ChangeUsernameRequest;
use App\Http\Requests\User\Settings\SelfEditRequest;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected UserService $service;

    private ?User $user;

    /**
     * SettingsController constructor.
     *
     * @param \App\Services\UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:user'])->except('deleteAccount');
        $this->user = \auth()->guard(BaseAppGuards::USER)->user();
    }

    /**
     *
     * @param \App\Http\Requests\User\Settings\ChangePhotoRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function changePhoto(ChangePhotoRequest $request): Response
    {
        $data = $this->service->editInfo($request);

        return response(compact('data'), Response::HTTP_OK);
    }

    /**
     * Change user password
     *
     * @param \App\Http\Requests\User\Settings\ChangePasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function changePassword(ChangePasswordRequest $request): Response
    {
        $this->service->changePassword($request->validated(), Auth::guard(BaseAppGuards::USER)->id());

        return \response()->noContent(Response::HTTP_NO_CONTENT);
    }

    public function changeUsername(ChangeUsernameRequest $request): Response
    {
        $user     = $this->service->changeUsername(
            Auth::guard(BaseAppGuards::USER)->user(),
            $request->validated()['user_name']
        );

        $userData = new UserDataResource($user);

        return \response(compact('userData'), Response::HTTP_OK);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function deleteAccount(Request $request): mixed
    {
        $token = $request->bearerToken();

        return $this->service->deleteAccount($this->user, $token);
    }
}
