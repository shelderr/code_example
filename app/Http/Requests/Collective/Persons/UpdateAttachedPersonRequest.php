<?php

namespace App\Http\Requests\Collective\Persons;

use App\Models\Collective;
use App\Models\Venue;
use App\Rules\Collective\YearsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttachedPersonRequest extends FormRequest
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
            'collective_person_id' => ['required', 'integer', 'min:1', Rule::exists('collective_person', 'id')],
            'person_id'            => ['integer', 'min:1', Rule::exists('persons', 'id')],
            'years'                => ['required', 'array'],
            'years.*'              => ['required_with:years', 'integer', 'digits:4', 'distinct', new YearsRule(), 'min:' . Collective::MIN_YEAR],
            'role'                 => ['string', 'min:2', 'max:1024'],
        ];
    }
}
