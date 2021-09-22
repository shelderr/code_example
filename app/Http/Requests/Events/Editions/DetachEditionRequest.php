<?php

namespace App\Http\Requests\Events\Editions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DetachEditionRequest extends FormRequest
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
            'edition_event_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('events', 'id')
                    ->where('is_original', 'false'),
            ],
        ];
    }
}
