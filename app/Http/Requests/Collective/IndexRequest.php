<?php

namespace App\Http\Requests\Collective;

use App\Models\Collective;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
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
            'paginate'              => ['integer', 'min:2', 'max:20'],
            'sorting'               => ['array'],
            'sorting.role_ids'      => ['array'],
            'sorting.role_ids.*'    => [
                'required_with:sorting.role_ids',
                'integer',
                Rule::exists('roles', 'id'),
            ],
            'sorting.country_ids'   => ['array'],
            'sorting.country_ids.*' => [
                'required_with:sorting.country_ids',
                'integer',
                Rule::exists('countries', 'id'),
            ],
            'sorting.order'         => ['string', Rule::in(Collective::$sortDir)],
        ];
    }
}
