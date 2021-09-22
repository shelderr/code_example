<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Exceptions\Model\NotFoundException;
use App\Models\Events\Awards;
use App\Models\User\Bookmarks\BookmarkFolder;
use App\Models\User\Bookmarks\Folderable;
use App\Repositories\Base\Repository;
use App\Services\Base\BaseAppGuards;
use App\Traits\UploadTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class AwardsRepository extends Repository
{
    use UploadTrait;

    public function model(): string
    {
        return Awards::class;
    }

    public function getAwards(int $eventId, int $paginate, string $type): LengthAwarePaginator
    {
        return $this->newQuery()
            ->where('event_id', '=', $eventId)
            ->whereType($type)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }
}
