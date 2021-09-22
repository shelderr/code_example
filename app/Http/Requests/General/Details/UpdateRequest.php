<?php

namespace App\Http\Requests\General\Details;

use App\Enums\BaseAppEnum;
use App\Models\Details;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
                Rule::in(Details::IMAGE_PERMISSIONS_ENUMS),
            ],
            'description'      => ['required', 'string', 'min:10'],
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'The document may not be greater than _10 megabytes'
        ];
    }
}
