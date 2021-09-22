<?php

namespace App\Http\Requests\Person;

use App\Models\Persons;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonIndexRequest extends FormRequest
{

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
            'sorting.alphabetical'  => ['string', 'regex:' . Persons::ALPHABETICAL_SORTING_REGEX],
            'sorting.oder'          => ['string', Rule::in(Persons::$sortingEnums)],
            'sorting.is_member'     => ['boolean',],
            'sorting.role_ids'      => ['array'],
            'sorting.role_ids.*'    => ['required_with:sorting.role_ids', 'integer', Rule::exists('roles', 'id')],
            'sorting.country_ids'   => ['array'],
            'sorting.country_ids.*' => [
                'required_with:sorting.country_ids',
                'integer',
                Rule::exists('countries', 'id'),
            ],
            'sorting.with_photo'    => ['boolean'],
        ];
    }
}
