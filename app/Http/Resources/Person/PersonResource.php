<?php

namespace App\Http\Resources\Person;

use App\Enums\BaseAppEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $paginate = $request->paginate ?? BaseAppEnum::DEFAULT_PAGINATION;

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'stage_name'      => $this->stage_name,
            'bio'             => $this->bio,
            'company'         => $this->company,
            'job'             => $this->job,
            'image'           => $this->image,
            'multiSizeImages' => $this->multiSizeImages,
            'image_author'    => $this->image_author,
            'image_source'    => $this->image_source,
            // 'image_permission' => $this->image_permission,
            'is_deceased'     => $this->is_deceased,
            'birth_place'     => $this->birth_place,
            'birth_year'      => $this->birth_year,
            'birth_month'     => $this->birth_month,
            'birth_day'       => $this->birth_day,
            'death_year'      => $this->death_year,
            'death_month'     => $this->death_month,
            'death_day'       => $this->death_day,
            'roles'           => $this->roles()->paginate($paginate),
            'countries'       => $this->countries,
            'images'          => $this->images()->paginate($paginate),
            'headshots'       => $this->headshots()->paginate($paginate),
            'videos'          => $this->videos()->paginate($paginate),
            'creator'         => $this->creator()->paginate($paginate),
            'cast'            => $this->cast()->paginate($paginate),
            'crew'            => $this->crew()->paginate($paginate),
            'details'         => $this->details()->paginate($paginate),
            'url_links'       => $this->links()->paginate($paginate),
            'is_member'       => $this->is_member,
            'trailers'        => $this->trailers,
            'links'           => [
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
