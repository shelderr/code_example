<?php

namespace App\Http\Requests\Collective\Persons;

use App\Models\Collective;
use App\Models\Events\Event;
use App\Models\Venue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachPersonRequest extends FormRequest
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
        $maxYear = date('Y') + Venue::MAX_SUB_YEAR;

        return [
            'collective_id' => ['required', 'integer', 'min:1', 'bail', Rule::exists('collectives', 'id')],
            'person_id'     => ['required', 'integer', 'min:1', 'bail', Rule::exists('persons', 'id')],
            'years'         => ['array'],
            'years.*'       => ['required_with:years', 'integer', 'digits:4', 'distinct', 'min:' . Collective::MIN_YEAR, "max:$maxYear"],
            'role'          => ['string', 'min:2', 'max:1024'],
        ];
    }
}
