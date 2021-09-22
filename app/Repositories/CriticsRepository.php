<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Collective;
use App\Models\Critics;
use App\Models\Details;
use App\Models\Events\Event;
use App\Models\Links;
use App\Models\Persons;
use App\Repositories\Base\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CriticsRepository extends Repository
{
    public function __construct(Application $app, Collection $collection = null)
    {
        parent::__construct($app, $collection);
    }

    public function model(): string
    {
        return Critics::class;
    }

    /**
     * @param     $data
     *
     * @return mixed
     */
    public function criticEntity($data): mixed
    {
        $entity      = null;
        $isExistType = in_array($data['target_type'], Critics::ALLOWED_TYPES);

        abort_if(! $isExistType, Response::HTTP_BAD_REQUEST, 'invalid target type');

        if ($data['target_type'] == Critics::EVENTS_ENTITY) {
            $entity = Event::where('type', '=', Event::TYPE_EVENT)->findOrFail($data['target_id']);
        }

        if ($data['target_type'] == Critics::SHOWS_ENTITY) {
            $entity = Event::where('type', '=', Event::TYPE_SHOW)->findOrFail($data['target_id']);
        }

        return $entity;
    }
}
