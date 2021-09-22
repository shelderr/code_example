<?php

namespace App\Services\Event;

use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\AccessDenyException;
use App\Exceptions\Model\NotFoundException;
use App\Models\User;
use App\Repositories\Event\BookmarkRepository;
use App\Repositories\Event\FolderRepository;
use App\Services\Base\BaseAppGuards;
use Illuminate\Database\Eloquent\Model;

class BookmarkService
{
    private FolderRepository $folderRepo;

    private BookmarkRepository $bookmarkRepo;

    private ?User $user;

    public function __construct()
    {
        $this->folderRepo   = resolve(FolderRepository::class);
        $this->bookmarkRepo = resolve(BookmarkRepository::class);
        $this->user         = auth()->guard(BaseAppGuards::USER)->user();
    }

    /**
     * Users folder
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function foldersIndex(int $paginate)
    {
        return $this->folderRepo->getUserFolders($this->user)->paginate($paginate);
    }

    /**
     * @param string $query
     * @param int    $pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \ReflectionException
     */
    public function searchFolder(string $query, int $pagination)
    {
        if (! is_null($query)) {
            $query  = trim(mb_strtolower($query));
            $search = escapeLike($query);

            return User\Bookmarks\BookmarkFolder::where('user_id', '=', (int) $this->user->id)->where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(name) LIKE ?',
                        [$search]
                    );
                }
            )->paginate($pagination);
        }

        return $this->folderRepo->paginate($pagination);
    }

    /**
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createFolder(string $name)
    {
        return $this->folderRepo->create(
            [
                'name'    => $name,
                'user_id' => $this->user->id,
            ]
        );
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function showFolder(int $id)
    {
        return $this->folderRepo->getUserFolder($id, $this->user);
    }

    /**
     * @param array $data
     * @param       $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function update(array $data, $id): ?Model
    {
        $folder = $this->folderRepo->findOrFail($id);

        if ($folder->user_id !== $this->user->id) {
            throw new NotFoundException(ErrorMessages::FOLDER_NOT_FOUND);
        }

        $folder->update($data);

        return $folder->fresh();
    }

    /**
     * @param int $id
     *
     * @return bool|null
     */
    public function deleteFolder(int $id): ?bool
    {
        return $this->folderRepo->getUserFolder($id, $this->user)->delete();
    }

    /**
     * @param array $data
     *
     * @return mixed
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function deleteBookmark(array $data)
    {
        return $this->bookmarkRepo->deleteBookmark($data['folder_id'], $data['entity_id'], $data['type']);
    }
}
