<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\Event\FolderRepository;

class UserObserver
{
    private FolderRepository $folderRepository;

    public function __construct()
    {
        $this->folderRepository = resolve(FolderRepository::class);
    }

    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function created(User $user)
    {
        //Creating default bookmark for registered user
        $this->folderRepository->create(
            [
                'name'    => 'Favorites',
                'user_id' => $user->id,
            ]
        );
    }

    /**
     * Handle the User "updated" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
