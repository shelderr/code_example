<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Exceptions\Model\NotFoundException;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Models\User\Bookmarks\Folderable;
use App\Repositories\Base\Repository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;

class BookmarkRepository extends Repository
{
    use UploadTrait;

    public function model(): string
    {
        return Folderable::class;
    }

    /**
     * @param int    $folderId
     * @param int    $entityId
     * @param string $type
     *
     * @return mixed
     * @throws \App\Exceptions\Model\NotFoundException
     */
    public function deleteBookmark(int $folderId, int $entityId, string $type)
    {
        $folderableType = BookmarkFolder::$relationsEntity[$type];
        $folder         = BookmarkFolder::findOrFail($folderId);

        if ($folder->user_id !== auth()->guard(BaseAppGuards::USER)->user()->id) {
            throw new NotFoundException('Folder not found');
        }

        $bookmark = $this->newQuery()
            ->where('bookmark_folder_id', '=', $folderId)
            ->where('folderable_type', '=', $folderableType)
            ->where('folderable_id', '=', $entityId);

        if ($bookmark->get()->isEmpty()) {
            throw new NotFoundException('Bookmark not found');
        }

        return $bookmark->delete();
    }
}
