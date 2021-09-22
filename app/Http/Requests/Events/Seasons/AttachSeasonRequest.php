<?php

namespace App\Http\Requests\Events\Seasons;

use App\Enums\BaseAppEnum;
use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachSeasonRequest extends FormRequest
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
        $maxYear = date('Y') + Event::MAX_SUB_YEAR;

        return [
            'event_id'         => [
                'required',
                'integer',
                'min:1',
                'bail',
                Rule::exists('events', 'id')->where('type', Event::TYPE_SHOW),
            ],
            'image'            => [
                'required_with:poster_author,poster_source,poster_permission',
                'file',
                'mimes:png,jpg,jpeg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'image_author'     => ['required_with:poster', 'string', 'min:2', 'max:255'],
            'image_source'     => ['required_with:poster', 'string', 'min:2', 'max:500'],
            'image_permission' => [
                'required_with:poster',
                'string',
                Rule::in(Event::EVENT_POSTER_PERMISSION_ENUMS),
            ],
            'description'      => ['required', 'string', 'min:1'],
            'years'            => ['array'],
            'years.*'          => [
                'required_with:years',
                'integer',
                'digits:4',
                'distinct',
                'min:' . Event::MIN_YEAR,
                "max:$maxYear",
            ],
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The document may not be greater than _10 megabytes'
        ];
    }
}
