<?php

namespace App\Http\Requests\Events\Venues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachVenueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id'              => ['required', 'integer', 'min:1', 'bail', Rule::exists('events', 'id')],
            'venue_id'              => ['required', 'integer', 'min:1', Rule::exists('venues', 'id')],
            'start_year'            => ['integer', 'digits:4'],
            'start_month'           => ['integer', 'min:1', 'max:12'],
            'start_day'             => ['integer', 'min:1', 'max:31'],
            'end_year'              => ['integer', 'digits:4'],
            'end_month'             => ['integer', 'min:1', 'max:12'],
            'end_day'               => ['integer', 'min:1', 'max:31'],
        ];
    }
}
