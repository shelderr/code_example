<?php

namespace App\Http\Resources\Venue;

use App\Enums\BaseAppEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'native_name'       => $this->native_name,
            'alternative_names' => $this->alternative_names,
            'description'       => $this->description,
            'image'             => $this->image,
            'multiSizeImages'   => $this->multiSizeImages,
            'image_author'      => $this->image_author,
            'image_source'      => $this->image_source,
            'image_permission'  => $this->image_permission,
            'is_active'         => $this->is_active,
            'country'           => $this->country,
            'city'              => $this->city,
            'street_address'    => $this->street_address,
            'coordinates'       => $this->coordinates,
            'seating_capacity'  => $this->seating_capacity,
            'category'          => $this->category,
            'opening_year'      => $this->opening_year,
            'opening_month'     => $this->opening_month,
            'opening_day'       => $this->opening_day,
            'shows'             => $this->shows()->paginate($paginate),
            'events'            => $this->events()->paginate($paginate),
            'images'            => $this->images()->paginate($paginate),
            'links'             => [
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
