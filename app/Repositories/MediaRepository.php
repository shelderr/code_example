<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Media;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class MediaRepository extends Repository
{
    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
    }

    public function model(): string
    {
        return Media::class;
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function index(string $type): mixed
    {
        return $this->newQuery()->whereType($type);
    }
}
