<?php

namespace App\Http\Requests\Events\Persons;

use App\Models\Events\Event;
use App\Models\Events\EventPerson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttachPersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'event_id'      => ['required', 'integer', 'min:1', 'bail', Rule::exists('events', 'id')],
            'person_type'   => [
                'required',
                'string',
                Rule::in(EventPerson::$allTypes),
            ],
            'is_future'     => ['required', 'boolean'],
            'is_original'   => ['required_if:person_type,==,' . Event::RELATION_CASTS, 'boolean'],
            'person_id'     => ['required_without:collective_id', 'integer', 'min:1', Rule::exists('persons', 'id')],
            'collective_id' => [
                'required_without:person_id',
                'integer',
                'min:1',
                Rule::exists('collectives', 'id'),
            ],
            'years'         => ['json'],
            'years.*'       => ['required_with:years', 'integer', 'digits:4', 'distinct'],
            'event_roles'   => [
                'array',
                'bail',
                'min:0',
                'max:1'
                //new AttachPersonRole() in multiple case
            ],
            'event_roles.*' => [
                'required_with:event_roles',
                'string',
                'nullable',
                'min:2',
                'max:255',
                'distinct',
            ],
            'award_ids' => ['array'],
            'award_ids.*' => [
                'required_with:award_ids',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('awards', 'id')
                    ->where('event_id', $request->event_id)
                    ->where('type', EventPerson::RELATION_JURY),
            ],
        ];
    }
}
