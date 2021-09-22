<?php

namespace App\Http\Requests\Venue\Media;

use App\Enums\BaseAppEnum;
use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachImagesRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'venue_id'   => ['required', 'integer', 'min:1', 'bail', Rule::exists('venues', 'id')],
            'type'       => ['required', 'string', Rule::in([Media::TYPE_IMAGES])],
            'file'       => [
                'required',
                'file',
                'mimes:png,jpg,jpeg',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'author'     => ['required', 'string', 'min:2', 'max:255'],
            'source'     => ['required', 'string', 'min:2', 'max:512'],
            'permission' => ['required', 'string', Rule::in(Media::ALL_PERMISSIONS)],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'The document may not be greater than _10 megabytes'
        ];
    }
}
