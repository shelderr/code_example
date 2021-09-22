<?php

namespace App\Observers;

use App\Models\Events\Event;
use Elasticsearch\ClientBuilder;

class ElasticsearchObserver
{
    private $elasticsearch;

    public function __construct()
    {
        $host                = config('elasticsearch.connections.default.hosts');
        $this->elasticsearch = ClientBuilder::create()->setHosts($host)->build();
    }

    /**
     * Handle the model "created" event.
     *
     * @param $model
     *
     * @return void
     */
    public function created($model)
    {
        if ($model->getSearchIndex() == 'events') {
            if ($model->type == Event::TYPE_SHOW) {
                $index = Event::TYPE_SHOW;
            } else {
                $index = Event::TYPE_EVENT;
            }
        } else {
            $index = $model->getSearchIndex();
        };

        $data = [
            'index' => $index,
            'type'  => $model->getSearchType(),
            'id'    => $model->getKey(),
            'body'  => $model->toSearchArray(),
        ];

        $this->elasticsearch->index($data);
    }

    /**
     * Handle the model "updated" event.
     *
     * @param $model
     *
     * @return void
     */
    public function updated($model)
    {
        $index = [
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->getKey(),
            'body'  => $model->toSearchArray(),
        ];

        $searchIndex = array_slice($index, 0, 3);

        if (! $model->is_blocked) {
            if ($this->elasticsearch->exists($searchIndex)) {
                $this->elasticsearch->delete(array_slice($index, 0, 3));
            }

            $this->elasticsearch->index($index);
        } else {
            if ($this->elasticsearch->exists($searchIndex)) {
                $this->elasticsearch->delete(array_slice($index, 0, 3));
            }
        }
    }

    /**
     * Handle the model "deleted" event.
     *
     * @param $model
     *
     * @return void
     */
    public function deleted($model)
    {
        $index = [
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->getKey(),
        ];

        $this->elasticsearch->delete($index);
    }

    /**
     * Handle the model "restored" event.
     *
     * @param $model
     *
     * @return void
     */
    public function restored($model)
    {
        $index = [
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->getKey(),
            'body'  => $model->toSearchArray(),
        ];

        $this->elasticsearch->index($index);
    }

    /**
     * Handle the model "force deleted" event.
     *
     * @param $model
     *
     * @return void
     */
    public function forceDeleted($model)
    {
        //
    }
}
