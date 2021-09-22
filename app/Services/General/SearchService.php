<?php

namespace App\Services\General;

use App\Models\Collective;
use App\Models\Events\Event;
use App\Models\Persons;
use App\Models\Venue;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class SearchService
{
    private Client $es;

    public function __construct()
    {
        $host = config('elasticsearch.connections.default.hosts');

        $this->es = ClientBuilder::create()->setHosts($host)->build();
    }

    /**
     * Search by db
     *
     * @param string $query
     * @param int    $paginate
     * @param array  $target
     *
     * @return array[]
     */
    public function search(string $query, int $paginate, array $target = []): array
    {
        //TODO: REFACTOR DB SEARCH
        $query  = trim(mb_strtolower($query));
        $search = escapeLike($query);

        $responseData = [];

        if (in_array('show', $target)) {
            $responseData['shows'] = Event::where('type', Event::TYPE_SHOW)->where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(title) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($paginate);
        }

        if (in_array('event', $target)) {
            $responseData['events'] = Event::where('type', Event::TYPE_EVENT)->where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(title) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($paginate);
        }

        if (in_array('person', $target)) {
            $responseData['persons'] = Persons::where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(name) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($paginate);
        }

        if (in_array('collective', $target)) {
            $responseData['collectives'] = Collective::where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(name) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($paginate);
        }

        if (in_array('venues', $target)) {
            $responseData['venues'] = Venue::where(
                function ($q) use ($search) {
                    $q->whereRaw(
                        'LOWER(name) LIKE ?',
                        [$search]
                    );
                }
            )->orderBy('created_at', 'desc')->paginate($paginate);
        }

        return [$responseData];
    }

    public function elasticsearch(string $query): array
    {
        $query = escapeElasticReservedChars($query);

        if (strlen($query) <= 2) {
            $query = "*$query*";
        } else {
            $query = "$query~5";
        }

        return [
            'persons'    => Persons::count() >= 1 ? $this->es->search($this->esBody('persons', $query)) : null,
            'venues'     => Venue::count() >= 1 ? $this->es->search($this->esBody('venues', $query)) : null,
            'collective' => Collective::count() >= 1 ? $this->es->search($this->esBody('collectives', $query)) : null,
            'events'     => Event::whereType(Event::TYPE_EVENT)->count() >= 1 ? $this->es->search(
                $this->esBody('event', $query)
            ) : null,
            'shows'      => Event::whereType(Event::TYPE_SHOW)->count() >= 1 ? $this->es->search(
                $this->esBody('show', $query)
            ) : null,
        ];
    }

    private function esBody(string $index, string $query)
    {
        return [
            'index' => $index,
            'body'  => [
                'query' => [
                    'query_string' => [
                        'query'  => "$query",
                        "fields" => ['name', 'title'],
                    ],
                ],
                'size'  => 100,
            ],
        ];
    }

}
