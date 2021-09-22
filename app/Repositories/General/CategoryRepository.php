<?php

declare(strict_types=1);

namespace App\Repositories\General;

use App\Models\Category;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class CategoryRepository extends Repository
{
    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
    }

    public function model(): string
    {
        return Category::class;
    }

    /**
     * @return mixed
     */
    public function index(string $type)
    {
        return $this->newQuery()->whereType($type)
            ->with('childrens')
            ->orderBy('id', 'asc')
            ->get();
    }
}
