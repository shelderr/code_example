<?php

namespace App\Http\Resources\Events;

use App\Enums\BaseAppEnum;
use App\Models\Events\Event;
use App\Models\Persons;
use App\Traits\PaginateCollectionTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Lcobucci\JWT\Builder;

class EventResource extends JsonResource
{
    use PaginateCollectionTrait;

    public function toArray($request)
    {
        $paginate = $request->paginate ?? BaseAppEnum::DEFAULT_PAGINATION;

        return [
            'id'                  => $this->id,
            'title_original_lang' => $this->title_original_lang,
            'title'               => $this->title,
            'slogan'              => $this->slogan,
            'type'                => $this->type,
            'is_active'           => $this->is_active,
            'is_original'         => $this->is_original,
            'company_name'        => $this->company_name,
            'poster'              => $this->poster,
            'multiSizeImages'     => $this->multiSizeImages,
            'poster_author'       => $this->poster_author,
            'poster_source'       => $this->poster_source,
            'poster_permission'   => $this->poster_permission,
            'description'         => $this->description,
            'tickets_url'         => $this->tickets_url,
            'producer'            => null,
            'president'           => null,
            'countries_created'   => $this->countriesCreated,
            'countries_presented' => $this->countriesPresented,
            'city'                => $this->city,
            'languages'           => $this->languages,
            'production_types'    => $this->productionTypes,
            'show_audience'       => $this->showAudience,
            'show_types'          => $this->showTypes,
            'event_types'         => $this->eventTypes,
            'start_year'          => $this->start_year,
            'start_month'         => $this->start_month,
            'start_day'           => $this->start_day,
            'end_year'            => $this->end_year,
            'end_month'           => $this->end_month,
            'end_day'             => $this->end_day,
            'established_year'    => $this->established_year,
            'established_month'   => $this->established_month,
            'established_day'     => $this->established_day,
            'is_television'       => $this->is_television,
            'rating'              => $this->rating,
            'userRating'          => $this->userRating,
            'posters'             => $this->posters()->paginate($paginate),
            'images'              => $this->images()->paginate($paginate),
            'videos'              => $this->videos()->paginate($paginate),
            'trailers'            => $this->trailers()->paginate($paginate),
            'creators'            => $this->creators()->paginate($paginate),
            'casts'               => $this->casts()->paginate($paginate),
            'crew'                => $this->crew()->paginate($paginate),
            'futureCreators'      => $this->futureCreators()->paginate($paginate),
            'futureCasts'         => $this->futureCasts()->paginate($paginate),
            'futureCrew'          => $this->futureCrew()->paginate($paginate),
            'jury'                => $this->jury()->paginate($paginate),
            'futureJury'          => $this->futureJury()->paginate($paginate),
            'acts'                => $this->acts()->orderBy('created_at', 'asc')->paginate($paginate),
            'futureActs'          => $this->futureActs()->orderBy('created_at', 'asc')->paginate($paginate),
            'childrenEditions'    => $this->childrenEditions()->paginate($paginate),
            'parentEdition'       => $this->parentEdition()->paginate($paginate),
            'seasons'             => $this->seasons()->paginate($paginate),
            'details'             => $this->details()->paginate($paginate),
            'critics'             => $this->critics()->paginate($paginate),
            'url_links'           => $this->links()->paginate($paginate),
            'venue'               => $this->venue,
            'links'               => [
                'wikipedia_url' => $this->wikipedia_url,
                'wikidata_url'  => $this->wikidata_url,
                'facebook_url'  => $this->facebook_url,
                'youtube_url'   => $this->youtube_url,
                'twitter_url'   => $this->twitter_url,
                'instagram_url' => $this->instagram_url,
                'linkedin_url'  => $this->linkedin_url,
                'vk_url'        => $this->vk_url,
                'tiktok_url'    => $this->tiktok_url,
                'telegram_url'  => $this->telegram_url,
                'web_url'       => $this->web_url,
            ],

        ];
    }
}
