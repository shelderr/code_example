<?php

namespace App\Http\Requests\Collective;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Category;
use App\Models\Collective;
use App\Models\Venue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCollectiveRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'             => ['required', 'string', 'min:1', 'max:255', Rule::unique('collectives', 'name')],
            'other_name'       => ['string', 'min:1', 'max:255', Rule::unique('collectives', 'other_name')],
            'image'            => [
                'required_with:image_author,image_source,image_permission',
                'file',
                'mimes:png,jpg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'image_author'     => ['required_with:image', 'string', 'min:2', 'max:255'],
            'image_source'     => ['required_with:image', 'string', 'min:2', 'max:255'],
            'image_permission' => [
                'required_with:image',
                'string',
                'min:2',
                'max:255',
                Rule::in(Collective::IMAGE_PERMISSION_ENUM),
            ],
            'bio'              => ['string', 'min:2'],
            'category_id'      => [
                'integer',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')->where(
                    function (Builder $builder) {
                        $builder->where('type', '=', Category::COLLECTIVE_CATEGORY);
                    }
                ),
            ],
            'countries'        => ['array'],
            'countries.*'      => [
                'required_with:countries',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('countries', 'id'),
            ],
            'facebook_url'     => ['string', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
            'wikipedia_url'    => ['string', 'max:512', 'regex:' . SocialLinksEnum::WIKIPEDIA_REGEX],
            'youtube_url'      => ['string', 'max:512', 'regex:' . SocialLinksEnum::YOUTUBE_REGEX],
            'twitter_url'      => ['string', 'max:512', 'regex:' . SocialLinksEnum::TWITTER_REGEX],
            'instagram_url'    => ['string', 'max:512', 'regex:' . SocialLinksEnum::INSTAGRAM_REGEX],
            'linkedin_url'     => ['string', 'max:512', 'regex:' . SocialLinksEnum::LINKEDIN_REGEX],
            'vk_url'           => ['string', 'max:512', 'regex:' . SocialLinksEnum::VK_REGEX],
            'tiktok_url'       => ['string', 'nullable', 'max:512', 'regex:' . SocialLinksEnum::TIKTOK_REGEX],
            'telegram_url'     => ['string', 'max:512', 'regex:' . SocialLinksEnum::TELEGRAM_REGEX],
            'web_url'          => ['string', 'max:512', 'regex:' . SocialLinksEnum::WEB_URL],
            'wikidata_url'     => ['string', 'max:512', 'regex:' . SocialLinksEnum::WIKIDATA_REGEX],
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The document may not be greater than _10 megabytes',
        ];
    }
}
