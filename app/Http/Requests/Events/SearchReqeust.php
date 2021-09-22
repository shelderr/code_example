<?php

namespace App\Http\Requests\Events;

use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchReqeust extends FormRequest
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
            'type'     => ['required', 'string', Rule::in(Event::ALL_EVENTS)],
        ];
    }
}
