<?php

namespace App\Http\Requests\Events\Venues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DetachVenueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id' => ['required', 'integer', 'min:1', 'bail', Rule::exists('events', 'id')],
            'venue_id' => ['required', 'integer', 'min:1', 'bail', Rule::exists('venues', 'id')]
        ];
    }
}
