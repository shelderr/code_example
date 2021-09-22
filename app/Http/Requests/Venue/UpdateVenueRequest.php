<?php

namespace App\Http\Requests\Venue;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Category;
use App\Models\Venue;
use App\Rules\Venue\CoordinatesRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateVenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $maxYear = date('Y') + Venue::MAX_SUB_YEAR;

        return [
            'name'              => ['string', 'min:1', 'max:255', Rule::unique('venues', 'name')->ignore($request->id)],
            'native_name'       => ['string', 'min:1', 'max:255'],
            'alternative_names' => ['string', 'min:1', 'max:512'],
            'description'       => ['string', 'min:1'],
            'image'             => [
                'file',
                'mimes:png,jpg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'image_author'      => ['required_with:image', 'string', 'min:2', 'max:255'],
            'image_source'      => ['required_with:image', 'string', 'min:2', 'max:500'],
            'image_permission'  => [
                'required_with:image',
                'string',
                'min:2',
                'max:255',
                Rule::in(Venue::IMAGE_PERMISSION_ENUM),
            ],
            'is_active'         => ['boolean', 'nullable'],
            'country_id'        => ['integer', 'nullable', 'min:1', Rule::exists('countries', 'id')],
            'city'              => ['nullable', 'string', 'min:1', 'max:255'],
            'street_address'    => ['required_with:country_id', 'nullable', 'string', 'min:1', 'max:512'],
            'coordinates'       => ['required_with:country_id', 'nullable', 'string', new CoordinatesRule()],
            'seating_capacity'  => ['string', 'nullable', 'min:2', 'max:255'],
            'category_id'       => [
                'integer',
                'min:1',
                'nullable',
                'bail',
                Rule::exists('categories', 'id')->where(
                    function (Builder $q) {
                        $q->where('type', '=', Category::VENUE_CATEGORY);
                    }
                ),
            ],
            'opening_year'      => ['integer', 'nullable', 'digits:4', 'min:' . Venue::MIN_YEAR, "max:$maxYear"],
            'opening_month'     => ['integer', 'nullable', 'min:1', 'max:12'],
            'opening_day'       => ['integer', 'nullable', 'min:1', 'max:31'],
            'facebook_url'      => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
            'wikipedia_url'     => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIPEDIA_REGEX],
            'youtube_url'       => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::YOUTUBE_REGEX],
            'twitter_url'       => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TWITTER_REGEX],
            'instagram_url'     => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::INSTAGRAM_REGEX],
            'linkedin_url'      => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::LINKEDIN_REGEX],
            'vk_url'            => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::VK_REGEX],
            'tiktok_url'        => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TIKTOK_REGEX],
            'telegram_url'      => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TELEGRAM_REGEX],
            'web_url'           => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WEB_URL],
            'wikidata_url'      => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::WIKIDATA_REGEX],
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The document may not be greater than _10 megabytes',
        ];
    }
}
