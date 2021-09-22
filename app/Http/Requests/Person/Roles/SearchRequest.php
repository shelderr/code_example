<?php

namespace App\Http\Requests\Person\Roles;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'search'   => ['required', 'string', 'min:3', 'max:255'],
            'paginate' => ['integer', 'min:2', 'max:20'],
        ];
    }
}
