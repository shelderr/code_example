<?php

namespace App\Http\Requests\General\Search;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query'    => ['required', 'string', 'min:1', 'max:255'],
            'target'   => ['string', 'min:1', 'max:255'],
            'paginate' => ['integer', 'min:2', 'max:20'],
        ];
    }
}
