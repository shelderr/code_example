<?php

namespace App\Http\Requests\Events\Acts;

use App\Enums\BaseAppEnum;
use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActRequest extends FormRequest
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
            'title'            => ['required', 'string', 'min:1'],
            'description'      => ['string', 'min:2'],
            'link'             => [ 'string', 'min:2'],
            'persons_ids'       => ['array', 'nullable'],
            'persons_ids.*'     => [
                'required_with:ids',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('persons', 'id'),
            ],
            'collectives_ids'   => ['array', 'nullable'],
            'collectives_ids.*' => [
                'required_with:ids',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('collectives', 'id'),
            ],
            'is_future'         => ['boolean'],
            'shows_ids'         => ['array', 'nullable'],
            'shows_ids.*'       => [
                'required_with:ids',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('events', 'id')->where('type', Event::TYPE_SHOW),
            ],
            'image'            => [
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
                Rule::in(Event::EVENT_POSTER_PERMISSION_ENUMS),
            ],
        ];
    }
}
