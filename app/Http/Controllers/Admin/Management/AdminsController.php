<?php

namespace App\Http\Controllers\Admin\Management;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Management\Admins\EditRequest;
use App\Http\Requests\Admin\Management\Admins\IndexRequest;
use App\Http\Requests\Admin\Management\Admins\InviteRequest;
use App\Http\Resources\Admin\Management\Admins\AdminShow;
use App\Models\Admin;
use App\Services\Admin\AdminsManageService;
use App\Services\Admin\PermissionsManageService;
use App\Services\Base\BaseAppGuards;
use Illuminate\Http\Response;

class AdminsController extends Controller
{
    private AdminsManageService $service;

    private PermissionsManageService $permissionsService;

    private ?Admin $admin;

    public function __construct(AdminsManageService $service, PermissionsManageService $permissionsManageService)
    {
        $this->service = $service;
        $this->middleware(['auth:' . BaseAppGuards::ADMIN]);
        $this->permissionsService = $permissionsManageService;
        $this->admin              = auth()->guard(BaseAppGuards::ADMIN)->user();
    }

    public function index(IndexRequest $request): Response
    {
        $pagination = $request->validated()['pagination'] ?? BaseAppEnum::DEFAULT_PAGINATION;
        $admins     = $this->service->index($pagination);

        return response(compact('admins'), Response::HTTP_OK);
    }

    public function show($id): Response
    {
        $foundedAdmin = $this->service->findOrFail($id);

        abort_if($foundedAdmin->isSuperAdmin(), Response::HTTP_NOT_FOUND);

        $admin = new AdminShow($foundedAdmin);

        return \response(compact('admin'), Response::HTTP_OK);
    }

    public function delete(int $id): Response
    {
        $this->service->delete($id);

        return \response()->noContent();
    }

    public function blockSwitch(int $id): Response
    {
        $foundedAdmin = $this->service->findOrFail($id);
        $admin        = $this->service->blockSwitch($foundedAdmin);

        return \response(compact('admin'), Response::HTTP_OK);
    }

    public function permissions()
    {
        $permissions = $this->permissionsService->permissions();

        return \response(compact('permissions'), Response::HTTP_OK);
    }

    public function edit(EditRequest $request, int $id): Response
    {
        $data  = $request->validated();
        $admin = $this->service->findOrFail($id);
        $admin = $this->service->edit(
            $data['firstname'] ?? null,
            $data['lastname'] ?? null,
            $data['permissions'],
            $admin
        );

        return response(compact('admin'), Response::HTTP_OK);
    }

    public function invite(InviteRequest $request): Response
    {
        $token = $this->service->invite($request->validated());

        return \response(compact('token'), Response::HTTP_OK);
    }
}
