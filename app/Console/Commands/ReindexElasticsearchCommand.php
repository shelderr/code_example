<?php

namespace App\Console\Commands;

use App\Models\Collective;
use App\Models\Events\Event;
use App\Models\Persons;
use App\Models\Venue;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class ReindexElasticsearchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex all elasticsearch entities ';

    private $elasticsearh;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $host               = config('elasticsearch.connections.default.hosts');
        $this->elasticsearh = ClientBuilder::create()->setHosts($host)->build();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Reindexing all models. This might take a while...');

        $this->deleteByIndex('persons');

        $this->elasticsearh->indices()->create($this->settings('persons'));

        foreach (Persons::cursor() as $person) {
            $this->elasticsearh->index(
                [
                    'index' => $person->getSearchIndex(),
                    'type'  => $person->getSearchType(),
                    'id'    => $person->getKey(),
                    'body'  => $person->toSearchArray(),
                ]
            );
        }
        $this->deleteByIndex('collectives');

        $this->info('25%');

        foreach (Collective::cursor() as $model) {
            $this->elasticsearh->index(
                [
                    'index' => $model->getSearchIndex(),
                    'type'  => $model->getSearchType(),
                    'id'    => $model->getKey(),
                    'body'  => $model->toSearchArray(),
                ]
            );
        }

        $this->info('50%');

        $this->deleteByIndex('events');

        foreach (Event::cursor() as $model) {

            if ($model->getSearchIndex() == 'events') {
                if ($model->type == Event::TYPE_SHOW) {
                    $index = Event::TYPE_SHOW;
                } else {
                    $index = Event::TYPE_EVENT;
                }
            } else {
                $index = $model->getSearchIndex();
            };

            $this->elasticsearh->index(
                [
                    'index' => $index,
                    'type'  => $model->getSearchType(),
                    'id'    => $model->getKey(),
                    'body'  => $model->toSearchArray(),
                ]
            );
        }

        $this->info('75%');

        $this->deleteByIndex('venues');
        foreach (Venue::cursor() as $model) {
            $this->elasticsearh->index(
                [
                    'index' => $model->getSearchIndex(),
                    'type'  => $model->getSearchType(),
                    'id'    => $model->getKey(),
                    'body'  => $model->toSearchArray(),
                ]
            );
        }

        $this->info('\nDONE!');
    }

    private function deleteByIndex(string $index)
    {
        try {
            $this->elasticsearh->indices()->delete(["index" => $index]);
        } finally {
            return true;
        }
    }

    private function settings(string $index)
    {
        return [
            'index' => $index,
            'body'  => [
                "settings" => [
                    "analysis" => [
                        "filter"   => [
                            "german_stop"     => [
                                "type"      => "stop",
                                "stopwords" => "_german_",
                            ],
                            "german_keywords" => [
                                "type"     => "keyword_marker",
                                "keywords" => ["Beispiel"],
                            ],
                            "german_stemmer"  => [
                                "type"     => "stemmer",
                                "language" => "light_german",
                            ],
                        ],
                        "analyzer" => [
                            "rebuilt_german" => [
                                "tokenizer" => "standard",
                                "title"    => [
                                    "lowercase",
                                    "german_stop",
                                    "german_keywords",
                                    "german_normalization",
                                    "german_stemmer",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

}
