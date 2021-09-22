<?php

namespace App\Http\Requests\Person\Trailers;

use App\Models\Events\EventTrailers;
use App\Models\Persons\PersonTrailers;
use App\Rules\Event\TrailerLinkRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTrailerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'type'     => ['required', 'string', 'min:2', 'max:25', Rule::in(PersonTrailers::TYPES), 'bail'],
            'url'      => [
                'required',
                'string',
                'min:10',
                'max:255',
                Rule::unique('event_trailers', 'url'),
                'bail',
                new TrailerLinkRule($request->type)
            ],
        ];
    }
}
