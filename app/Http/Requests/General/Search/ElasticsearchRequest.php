<?php

namespace App\Http\Requests\General\Search;

use Illuminate\Foundation\Http\FormRequest;

class ElasticsearchRequest extends FormRequest
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
            'search' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
