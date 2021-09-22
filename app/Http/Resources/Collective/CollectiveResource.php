<?php

namespace App\Http\Resources\Collective;

use App\Enums\BaseAppEnum;
use App\Models\Events\Event;
use App\Models\Persons;
use App\Traits\PaginateCollectionTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Lcobucci\JWT\Builder;

class CollectiveResource extends JsonResource
{
    public function toArray($request)
    {
        $paginate = $request->paginate ?? BaseAppEnum::DEFAULT_PAGINATION;

        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'other_name'       => $this->other_name,
            'bio'              => $this->bio,
            'image'            => $this->image,
            'multiSizeImages'  => $this->multiSizeImages,
            'image_author'     => $this->image_author,
            'image_source'     => $this->image_source,
            'category'         => $this->category,
            'persons'          => $this->persons()->orderBy('created_at', 'desc')->paginate($paginate),
            'countries'        => $this->countries()->paginate($paginate),
            'image_permission' => $this->image_permission,
            'images'           => $this->images()->paginate($paginate),
            'headshots'        => $this->headshots()->paginate($paginate),
            'videos'           => $this->videos()->paginate($paginate),
            'details'          => $this->details()->paginate($paginate),
            'trailers'         => $this->trailers,
            'url_links'        => $this->links()->paginate($paginate),
            'events'           => $this->events()->paginate($paginate),
            'shows'           => $this->shows()->paginate($paginate),
            'links'            => [
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
