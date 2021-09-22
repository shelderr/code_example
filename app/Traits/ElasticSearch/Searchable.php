<?php

namespace App\Traits\ElasticSearch;

use App\Observers\ElasticsearchObserver;

trait Searchable
{
    public static function bootSearchable()
    {
        //Run observer if elasticsearch enabled
        if (config('services.elasticsearch.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    /**
     * Return table name for elasticsearch indexing
     *
     * @return string
     */
    public function getSearchIndex()
    {
        return $this->getTable();
    }

    /**
     * Return type for elasticsearch indexing
     *
     * @return string
     */
    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }
        return $this->getTable();
    }

    /**
     * Returns array for elasticsearch indexing
     *
     * @return array
     */
    public function toSearchArray()
    {
        return $this->toArray();
    }
}
