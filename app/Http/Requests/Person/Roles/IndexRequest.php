<?php

namespace App\Http\Requests\Person\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id'  => ['integer', 'min:1', Rule::exists('roles', 'id')],
            'paginate' => ['integer', 'min:2', 'max:40'],
        ];
    }
}
