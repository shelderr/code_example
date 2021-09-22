<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Models\User;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Repositories\Base\Repository;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Builder;

class FolderRepository extends Repository
{
    use UploadTrait;

    public function model(): string
    {
        return BookmarkFolder::class;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getUserFolders(User $user): Builder
    {
        return $this->newQuery()->where('user_id', '=', $user->id)->orderBy('name');
    }

    /**
     * @param int              $folderId
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getUserFolder(int $folderId, User $user)
    {
        return $this->newQuery()
            ->where('user_id', '=', $user->id)
            ->findOrFail($folderId);
    }
}
