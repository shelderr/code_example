<?php

namespace App\Http\Requests\Person;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Persons;
use App\Rules\Persons\AttachPersonRole;
use App\Rules\Persons\DeathDateRule;
use App\Services\Base\BaseAppGuards;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name'          => ['string', 'min:2', 'max:255', 'regex:' . Persons::NAME_REGEX],
            'stage_name'    => ['string', 'nullable', 'min:2', 'max:255'],
            'company'       => ['string', 'nullable', 'min:2', 'max:255'],
            'job'           => ['string', 'nullable', 'max:255'],
            'bio'           => ['string', 'nullable', 'min:2'],
            'image'         => [
                'file',
                'mimes:png,jpg,jpeg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'image_author'  => ['required_with:image', 'string', 'min:2', 'max:255'],
            'image_source'  => ['required_with:image', 'string', 'min:2', 'max:500'],
            'countries'     => ['array'],
            'countries.*'   => [
                'required_with:countries',
                'integer',
                'min:1',
                'distinct',
                Rule::exists('countries', 'id'),
            ],
            'is_deceased'   => ['nullable', 'boolean'],
            'birth_year'    => ['integer', 'digits:4'],
            'birth_month'   => ['integer', 'min:1', 'max:12'],
            'birth_day'     => ['integer', 'min:1', 'max:31'],
            'death_year'    => [
                'bail',
                'integer',
                'digits:4',
                'bail',
                // new DeathDateRule($request->is_deceased),
            ],
            'death_month'   => ['integer', 'min:1', 'max:12'],
            'death_day'     => ['integer', 'min:1', 'max:31'],
            'birth_place'   => ['string', 'nullable', 'min:2', 'max:255'],
            'roles'         => ['array', new AttachPersonRole()],
            'roles.*'       => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('roles', 'id'),
            ],
            'facebook_url'  => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
            'wikipedia_url' => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIPEDIA_REGEX],
            'youtube_url'   => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::YOUTUBE_REGEX],
            'twitter_url'   => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TWITTER_REGEX],
            'instagram_url' => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::INSTAGRAM_REGEX],
            'linkedin_url'  => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::LINKEDIN_REGEX],
            'vk_url'        => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::VK_REGEX],
            'tiktok_url'    => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TIKTOK_REGEX],
            'telegram_url'  => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TELEGRAM_REGEX],
            'web_url'       => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WEB_URL],
            'wikidata_url'  => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIDATA_REGEX],
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The document may not be greater than _10 megabytes',
        ];
    }
}
