<?php

namespace App\Services\Admin;

use App\Enums\BaseAppEnum;
use App\Exceptions\Application\AlreadyCreatedException;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Exceptions\Model\NotFoundException;
use App\Mail\Admin\AdminInvited;
use App\Mail\Admin\Invite;
use App\Models\Admin;
use App\Models\Helpers\BaseUsersModelInterface;
use App\Repositories\AdminRepository;
use App\Services\Base\BaseAppGuards;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Mail;
use Str;
use Stripe\Exception\PermissionException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Throwable;

class AdminsManageService extends AdminRepository
{
    private ?Admin $loginAdmin;

    public function __construct(Application $app, Collection $collection = null)
    {
        $this->loginAdmin = auth()->guard(BaseAppGuards::ADMIN)->user();
        parent::__construct($app, $collection);
    }

    public function index(int $pagination): LengthAwarePaginator
    {
        if (! $this->loginAdmin->isSuperAdmin()) {
            throw new PermissionException(ErrorMessages::NO_PERMISSIONS, Response::HTTP_FORBIDDEN);
        }

        return $this->newQuery()
            ->where('super_admin', '=', false)
            ->orderBy('id', 'desc')
            ->paginate($pagination);
    }

    public function delete(int $id): bool
    {
        if (! $this->loginAdmin->isSuperAdmin()) {
            throw new PermissionException(ErrorMessages::NO_PERMISSIONS, Response::HTTP_FORBIDDEN);
        }

        $admin = $this->findOrFail($id);

        if ($admin->isSuperAdmin()) {
            throw new BadRequestException(ErrorMessages::ADMIN_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        return parent::delete($id);
    }

    public function blockSwitch(Admin $admin): Admin
    {
        if (! $this->loginAdmin->isSuperAdmin()) {
            throw new PermissionException(ErrorMessages::NO_PERMISSIONS, Response::HTTP_FORBIDDEN);
        }

        if ($admin->isSuperAdmin()) {
            throw new BadRequestException(ErrorMessages::ADMIN_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        DB::transaction(
            function () use ($admin) {
                $admin->blocked = $admin->blocked ? false : true;
                $admin->save();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );

        return $admin->fresh();
    }

    public function edit(?string $firstMame, ?string $lastName, array $permissions, Admin $admin): ADmin
    {
        if (! $this->loginAdmin->isSuperAdmin()) {
            throw new PermissionException(ErrorMessages::NO_PERMISSIONS, Response::HTTP_FORBIDDEN);
        }

        if ($admin->isSuperAdmin()) {
            throw new NotFoundException(ErrorMessages::ADMIN_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        return DB::transaction(
            function () use ($firstMame, $lastName, $permissions, $admin) {
                if (! is_null($firstMame) && ! is_null($lastName)) {
                    $admin->first_name = $firstMame ?? $admin->firstname;
                    $admin->last_name  = $lastName ?? $admin->lastname;
                    $admin->save();
                }

                $admin->permissions()->sync($permissions, true);

                return $admin;
            },
            3
        );
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Throwable
     */
    public function invite(array $data)
    {
        if (! $this->loginAdmin->isSuperAdmin()) {
            throw new AccessDeniedException(ErrorMessages::ACCESS_DENIED, Response::HTTP_FORBIDDEN);
        }

        return DB::transaction(
            function () use ($data) {
                $emailConfirmation = null;

                if ($this->newQuery()->where('email', $data['email'] ?? '')
                    ->first()) {
                    throw new AlreadyCreatedException('User already exist');
                }

                DB::beginTransaction();
                try {
                    if ($this->model instanceof BaseUsersModelInterface) {
                        $admin = new $this->model($data);
                        $admin->email_confirmed = false;
                        $admin->save();

                        $permissions = $data['permissions'] ?? null;

                        if (! is_null($permissions)) {
                            $admin->permissions()->sync($permissions);
                        }

                        if (! empty($admin->email) && filter_var($admin->email, FILTER_VALIDATE_EMAIL)) {
                            $emailConfirmation = Admin\EmailConfirmation::query()
                                ->where('email', $admin->email)
                                ->first();

                            if ($emailConfirmation) {
                                $emailConfirmation->delete();
                            }

                            $confirmToken      = Str::random($this->model::CONFIRM_TOKEN);
                            $emailConfirmation = Admin\EmailConfirmation::query()->create(
                                [
                                    'admin_id' => $admin->id,
                                    'email'    => $admin->email,
                                    'token'    => $confirmToken,
                                ]
                            );
                        }

                        $superAdmin = Admin::where('super_admin', '=', true)->first();

                        Mail::to($admin->email)
                            ->locale($this->model::DEFAULT_LANGUAGE)
                            ->queue(new Invite($emailConfirmation, $data['password'], $admin, BaseAppGuards::ADMIN));

                        Mail::to($superAdmin->email)
                            ->locale($this->model::DEFAULT_LANGUAGE)
                            ->queue(new AdminInvited($admin, $data['password'], BaseAppGuards::ADMIN));
                    }
                    DB::commit();
                } catch (Throwable $e) {
                    DB::rollBack();
                    throw new BadRequestException($e->getMessage());
                }

                return config('app.debug') ? ['email_confirm_token' => $emailConfirmation->token ?? ''] : null;
            },
            3
        );
    }
}
