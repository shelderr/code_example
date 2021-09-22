<?php

namespace App\Http\Requests\Person\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paginate' => ['integer', 'min:2', 'max:20'],
            'person_id' => ['required', 'integer', 'min:1', Rule::exists('persons', 'id')]
        ];
    }
}
