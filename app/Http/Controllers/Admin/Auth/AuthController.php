<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\Auth\AdminDataResource;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\AdminService;
use App\Services\Base\BaseAppGuards;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use FormatsErrorResponse;

    /**
     * @var \App\Services\AdminService
     */
    private AdminService $service;

    /**
     * AuthController constructor.
     *
     * @param \App\Services\AdminService $service
     */
    public function __construct(AdminService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:admin'])->except('login', 'confirmEmail');
    }

    /**
     * @param \App\Http\Requests\Admin\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     * @throws \App\Exceptions\Model\NotFoundException
     * @throws \ReflectionException
     */
    public function login(LoginRequest $request): Response
    {
        ['token' => $token, 'user_data' => $userData] = $this->service->authWithTOTP(
            $request->email,
            $request->password,
            $request->totp ?? '',
            'email',
            $request->remember ?? false
        );

        $userData = new AdminDataResource($userData);

        return response(compact('token', 'userData'), Response::HTTP_OK);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \App\Exceptions\Http\AccessDenyException
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function refreshToken(Request $request)
    {
        return response($this->service->tokenRefresh($request), Response::HTTP_OK);
    }

    /**
     * Get auth user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuth(Request $request): Response
    {
        $adminData = AdminDataResource::make(Auth::guard(BaseAppGuards::ADMIN)->user()->fresh());

        return response(compact('adminData'), Response::HTTP_OK);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout(): Response
    {
        $this->service->logout();

        return response()->noContent(Response::HTTP_NO_CONTENT);
    }

    public function confirmEmail(Request $request): bool
    {
        $request->validate(
            [
                'token' => 'required|string|max:255'
            ]
        );

        return  $this->service->confirmEmail($request->token);
    }
}
