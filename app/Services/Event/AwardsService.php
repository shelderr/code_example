<?php

namespace App\Services\Event;

use App\Enums\BaseAppEnum;
use App\Repositories\Event\AwardsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AwardsService extends AwardsRepository
{

    public function index(int $eventId, int $paginate, string $type): LengthAwarePaginator
    {
        return $this->getAwards($eventId, $paginate, $type);
    }

    /**
     * @throws \Throwable
     */
    public function createAward(array $data)
    {
        return \DB::transaction(
            function () use ($data) {
                parent::create($data);

                $paginate = $data['paginate'] ?? BaseAppEnum::DEFAULT_PAGINATION;

                return $this->getAwards($data['event_id'], $paginate, $data['type']);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }

    public function delete(int $id): bool
    {
        return \DB::transaction(
            function () use ($id) {
                return parent::delete($id);
            },
            BaseAppEnum::TRANSACTION_ATTEMPTS
        );
    }
}
