<?php

namespace App\Http\Requests\Venue\Media;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'venue_id' => ['required', 'integer', 'min:1', 'bail', Rule::exists('venues', 'id')],
            'type'      => [
                'required',
                'string',
                Rule::in([Media::TYPE_IMAGES]),
            ],
            'media_id'  => ['required', 'integer', 'min:1', Rule::exists('media', 'id')],
        ];
    }
}
