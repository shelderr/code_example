<?php

namespace App\Http\Requests\Events\Persons;

use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DetachPersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_person_id' => ['required', 'integer', 'min:1', 'bail', Rule::exists('event_person', 'id')]
        ];
    }
}
