<?php

namespace App\Http\Requests\Events;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Events\Event;
use App\Rules\Event\IsOriginalRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateEventRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        $maxYear = date('Y') + Event::MAX_SUB_YEAR;

        return [
            'title'                 => [
                'required',
                'string',
                'min:1',
                'max:255',
                Rule::unique('events', 'title')->where('type', Event::TYPE_EVENT),
            ],
            'title_original_lang'   => [
                'string',
                'min:1',
                'max:255',
                Rule::unique('events', 'title_original_lang')->where('type', Event::TYPE_EVENT),
            ],
            'slogan'                => ['string', 'min:3', 'max:255', Rule::unique('events', 'slogan')],
            'type'                  => ['required', 'string', Rule::in([Event::TYPE_EVENT])],
            //todo remove this param from request later
            'is_active'             => ['required', 'boolean'],
            'is_original'           => ['boolean'],
            'company_name'          => ['string', 'min:3', 'max:255'],
            'poster'                => [
                'required_with:poster_author,poster_source,poster_permission',
                'file',
                'mimes:png,jpg,jpeg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'poster_author'         => ['required_with:poster', 'string', 'min:2', 'max:255'],
            'poster_source'         => ['required_with:poster', 'string', 'min:2', 'max:500'],
            'poster_permission'     => [
                'required_with:poster',
                'string',
                Rule::in(Event::EVENT_POSTER_PERMISSION_ENUMS),
            ],
            'description'           => ['string', 'min:10'],
            'tickets_url'           => [
                'string',
                'min:10',
                'max:255',
                Rule::unique('events', 'tickets_url'),
            ],
            //TODO:FINISH VALIDATION
            'producer_id'           => ['integer'],
            'president_id'          => ['integer'],
            'languages'             => ['array'],
            'languages.*'           => ['integer', 'distinct', 'min:1', 'bail', Rule::exists('languages', 'id')],
            'start_year'            => ['integer', 'digits:4', 'min:' . Event::MIN_YEAR, "max:$maxYear"],
            'start_month'           => ['integer', 'min:1', 'max:12'],
            'start_day'             => ['integer', 'min:1', 'max:31'],
            'end_year'              => ['integer', 'digits:4', 'min:' . Event::MIN_YEAR, "max:$maxYear"],
            'end_month'             => ['integer', 'min:1', 'max:12'],
            'end_day'               => ['integer', 'min:1', 'max:31'],
            'established_year'      => ['integer', 'digits:4', 'min:' . Event::MIN_YEAR, "max:$maxYear"],
            'production_types'      => ['array'],
            'production_types.*'    => [
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('categories', 'id')
                    ->where('type', Event::PRODUCTION_TYPE_CATEGORY),
            ],
            'show_audience_id'      => [
                'integer',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')
                    ->where('type', Event::SHOW_AUDIENCE_CATEGORY),
            ],
            'event_types'           => ['required', 'array'],
            'event_types.*'         => [
                'required',
                'integer',
                'distinct',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')
                    ->where('type', Event::EVENT_TYPE_CATEGORY),
            ],
            'is_television'         => ['boolean'],
            'countries_created'     => ['array'],
            'countries_created.*'   => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('countries', 'id'),
            ],
            'countries_presented'   => ['array'],
            'countries_presented.*' => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('countries', 'id'),
            ],
            'city'                  => ['string', 'min:1', 'max:512'],
            'facebook_url'          => ['string', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
            'wikipedia_url'         => ['string', 'max:512', 'regex:' . SocialLinksEnum::WIKIPEDIA_REGEX],
            'youtube_url'           => ['string', 'max:512', 'regex:' . SocialLinksEnum::YOUTUBE_REGEX],
            'twitter_url'           => ['string', 'max:512', 'regex:' . SocialLinksEnum::TWITTER_REGEX],
            'instagram_url'         => ['string', 'max:512', 'regex:' . SocialLinksEnum::INSTAGRAM_REGEX],
            'linkedin_url'          => ['string', 'max:512', 'regex:' . SocialLinksEnum::LINKEDIN_REGEX],
            'vk_url'                => ['string', 'max:512', 'regex:' . SocialLinksEnum::VK_REGEX],
            'tiktok_url'            => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TIKTOK_REGEX],
            'telegram_url'          => ['string', 'max:512', 'regex:' . SocialLinksEnum::TELEGRAM_REGEX],
            'web_url'               => ['string', 'max:512', 'regex:' . SocialLinksEnum::WEB_URL],
            'wikidata_url'          => ['string', 'max:512', 'regex:' . SocialLinksEnum::WIKIDATA_REGEX],
        ];
    }

    public function messages()
    {
        return [
            'poster.max' => 'The document may not be greater than _10 megabytes',
        ];
    }
}
