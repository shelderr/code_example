<?php

namespace App\Services\Admin;

use App\Enums\BaseAppEnum;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\BadRequestException;
use App\Http\Resources\Admin\Management\Users\UsersActivityDataResource;
use App\Mail\Admin\User\Blocked;
use App\Mail\Admin\User\Unblocked;
use App\Mail\Admin\User\Verification\VerificationRejected;
use App\Models\Admin;
use App\Models\Persons;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Base\BaseAppGuards;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UsersManageService extends UserRepository
{
    private ?Admin $admin;

    public function __construct(Application $app, Collection $collection = null)
    {
        $this->admin = auth()->guard(BaseAppGuards::ADMIN)->user();
        parent::__construct($app, $collection);
    }

    /**
     * @param $pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function index($pagination)
    {
        return $this->model->with(User::$allRelations)
            ->orderBy('id', 'desc')
            ->paginate($pagination);
    }

    public function deleteRequestsIndex($pagination): LengthAwarePaginator
    {
        User::$showRemovedUserFields = true;

        return $this->model->with(User::$allRelations)
            ->orderBy('id', 'desc')
            ->where('is_deleted', '=', true)
            ->whereNull('deleted_at')
            ->paginate($pagination);
    }

    /**
     * User blocking
     *
     * @param $id - user id
     *
     * @return mixed
     * @throws \Throwable
     */
    public function blockSwitch($id)
    {
        $user = $this->findOrFail($id);

        DB::transaction(
            function () use ($user) {
                isset($user->user_name) ? $username = $user->user_name : $username = $user->email;

                if (! $user->blocked) {
                    Mail::to($user->email)->locale($this->model::DEFAULT_LANGUAGE)->queue(
                        new Blocked(
                            $username
                        )
                    );
                } else {
                    Mail::to($user->email)->locale($this->model::DEFAULT_LANGUAGE)->queue(
                        new Unblocked(
                            $username
                        )
                    );
                }

                $user->blocked = $user->blocked == false ? true : false;
                $user->save();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );

        return $user;
    }

    public function activity(int $id): JsonResource
    {
        $user = User::findOrFail($id);

        return new UsersActivityDataResource($user);
    }

    public function deleteUser(int $id)
    {
        DB::transaction(
            function () use ($id) {
                $user = $this->findOrFail($id);

                if ($user->personalityLink()->exists()) {
                    $user->personalityLink()->detach();
                }

                $user->update(
                    ['password' => null, 'user_name' => null, 'email' => null]
                );

                parent::delete($id);

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function rejectUserDelete(int $id)
    {
        return DB::transaction(
            function () use ($id) {
                $user = $this->findOrFail($id);

                if (! $user->is_deleted) {
                    throw new BadRequestException(ErrorMessages::USER_NOT_HAS_DELETE_REQUESTS);
                }

                $user->is_deleted = false;
                $user->save();

                return true;
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function verificationRequests(int $paginate)
    {
        return Persons\UserPerson::paginate($paginate);
    }

    /**
     * @param int    $userId
     * @param string $status
     *
     * @return mixed
     * @throws \App\Exceptions\Http\BadRequestException|\Throwable
     */
    public function verifyUserLink(
        int $userId,
        string $status
    ): mixed {
        return \DB::transaction(
            function () use ($userId, $status) {
                $user = $this->findOrFail($userId);

                if ($user->personalityLink()->wherePivot('status', '=', $this->model()::LINK_STATUS_ACCEPTED)->exists(
                ) && $status == $this->model()::LINK_STATUS_REJECTED) {
                    $personId = $user->personalityLink()->first()?->id;

                    return $user->personalityLink()->detach($personId);
                }

                $pendingPersons = $user->personalityLink()->wherePivot(
                    'status',
                    '=',
                    $this->model()::LINK_STATUS_PENDING
                );

                if (! $pendingPersons->exists() && $status == $this->model()::LINK_STATUS_ACCEPTED) {
                    throw new BadRequestException(ErrorMessages::USER_HAVE_NO_REQUESTS);
                }

                $person = $pendingPersons->first();

                if ($person->linkedUser()->wherePivot('status', '=', $this->model()::LINK_STATUS_ACCEPTED)->exists()) {
                    throw new BadRequestException(ErrorMessages::PERSON_ALREADY_VERIFIED);
                };

                if ($status === $this->model()::LINK_STATUS_REJECTED) {
                    Mail::to($user->email)->queue(new VerificationRejected($user, $person->id));

                    return $user->personalityLink()->detach($person->id);
                }

                $user->personalityLink()->updateExistingPivot($person->id, ['status' => $status]);

                return $person->linkedUser()->wherePivot('status', '=', $this->model()::LINK_STATUS_PENDING)->detach();
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
