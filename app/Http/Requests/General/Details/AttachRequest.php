<?php

namespace App\Http\Requests\General\Details;

use App\Enums\BaseAppEnum;
use App\Models\Details;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_id'        => ['required', 'integer', 'min:1'],
            'target_type'      => ['required', 'string', Rule::in(Details::ALLOWED_TYPES)],
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
