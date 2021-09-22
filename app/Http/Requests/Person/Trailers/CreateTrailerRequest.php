<?php

namespace App\Http\Requests\Person\Trailers;

use App\Models\Events\EventTrailers;
use App\Models\Persons\PersonTrailers;
use App\Rules\Event\TrailerLinkRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateTrailerRequest extends FormRequest
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
            'person_id'       => ['required', 'integer', 'min:1', Rule::exists('persons', 'id')],
            'trailers'        => ['required', 'array'],
            'trailers.*.type' => ['required', 'string', Rule::in(PersonTrailers::TYPES)],
            'trailers.*.url'  => [
                'required',
                'string',
                Rule::unique('event_trailers', 'url'),
                'bail',
                'min:10',
                'max:255',
                new TrailerLinkRule($request->trailers),
            ],
        ];
    }
}
