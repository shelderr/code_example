<?php

namespace App\Http\Requests\Events\Editions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttachEditionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'original_event_id'   => [
                'required',
                'integer',
                'min:1',
                'bail',
                Rule::exists('events', 'id')->where('is_original', 'true'),
            ],
            'edition_event_ids'   => ['array'],
            'edition_event_ids.*' => [
                'required_with:edition_event_ids',
                'integer',
                'min:1',
                'nullable',
                'distinct',
                'bail',
                Rule::exists('events', 'id')->where('is_original', 'false'),
            ],
        ];
    }
}
