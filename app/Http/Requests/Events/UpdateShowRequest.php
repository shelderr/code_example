<?php

namespace App\Http\Requests\Events;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Events\Event;
use App\Rules\Event\IsOriginalRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateShowRequest extends FormRequest
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
                'string',
                'min:1',
                'max:255',
                Rule::unique('events', 'title')
                    ->where('type', Event::TYPE_SHOW)
                    ->ignore($request->id),
            ],
            'title_original_lang'   => [
                'string',
                'nullable',
                'min:1',
                'max:255',
                Rule::unique('events', 'title_original_lang')
                    ->where('type', Event::TYPE_SHOW)
                    ->ignore($request->id),
            ],
            'slogan'                => [
                'string',
                'nullable',
                'min:3',
                'max:255',
                Rule::unique('events', 'slogan')->ignore($request->id),
            ],
            'is_active'             => ['boolean', 'nullable'],
            'company_name'          => ['string', 'nullable', 'min:2', 'max:255'],
            'poster'                => [
                'file',
                'mimes:png,jpg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'poster_author'         => ['required_with:poster', 'string', 'min:2', 'max:255'],
            'poster_source'         => ['required_with:poster', 'string', 'min:2', 'max:500'],
            'poster_permission'     => [
                'required_with:poster',
                'string',
                Rule::in(Event::EVENT_POSTER_PERMISSION_ENUMS),
            ],
            'description'           => ['string', 'nullable', 'min:10'],
            'tickets_url'           => [
                'string',
                'nullable',
                'min:10',
                'max:255',
                Rule::unique('events', 'tickets_url')->ignore($request->id),
            ],
            'languages'             => ['array'],
            'languages.*'           => ['integer', 'distinct', 'min:1', 'bail', Rule::exists('languages', 'id')],
            'established_year'      => ['integer', 'digits:4', 'min:' . Event::MIN_YEAR, "max:$maxYear"],
            'established_month'     => ['integer', 'min:1', 'max:12'],
            'established_day'       => ['integer', 'min:1', 'max:31'],
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
                'nullable',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')
                    ->where('type', Event::SHOW_AUDIENCE_CATEGORY),
            ],
            'show_types'            => ['required', 'array'],
            'show_types.*'          => [
                'required',
                'integer',
                'distinct',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')
                    ->where('type', Event::SHOW_TYPE_CATEGORY),
            ],
            'is_television'         => ['boolean', 'nullable'],
            'is_original'           => ['boolean', new IsOriginalRule($request->id)],
            'countries_created'     => ['array'],
            'countries_created.*'   => [
                'required_with:countries_created',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('countries', 'id'),
            ],
            'countries_presented'   => ['array'],
            'countries_presented.*' => [
                'required_with:countries_presented',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('countries', 'id'),
            ],
            'facebook_url'          => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
            'wikipedia_url'         => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIPEDIA_REGEX],
            'youtube_url'           => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::YOUTUBE_REGEX],
            'twitter_url'           => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TWITTER_REGEX],
            'instagram_url'         => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::INSTAGRAM_REGEX],
            'linkedin_url'          => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::LINKEDIN_REGEX],
            'vk_url'                => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::VK_REGEX],
            'tiktok_url'            => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TIKTOK_REGEX],
            'telegram_url'          => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TELEGRAM_REGEX],
            'web_url'               => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WEB_URL],
            'wikidata_url'          => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIDATA_REGEX],
        ];
    }

    public function messages()
    {
        return [
            'poster.max' => 'The document may not be greater than _10 megabytes'
        ];
    }
}
