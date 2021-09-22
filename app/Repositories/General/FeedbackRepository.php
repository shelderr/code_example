<?php

declare(strict_types=1);

namespace App\Repositories\General;

use App\Models\Feedback;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class FeedbackRepository extends Repository
{
    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
    }

    public function model(): string
    {
        return Feedback::class;
    }

    public function index(int $paginate)
    {
        return $this->newQuery()->with('person')->paginate($paginate);
    }
}
