<?php

namespace App\Http\Requests\Events\Awards;

use App\Models\Events\Awards;
use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateAwardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'event_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('events', 'id')
                    ->where('type', Event::TYPE_EVENT),
            ],
            'name'     => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('awards')
                    ->where('event_id', $request->event_id)
                    ->where('type', Awards::getTypes()),
            ],
            'type'     => ['required', 'string', Rule::in(Awards::getTypes())],
            'paginate' => ['integer', 'min:1', 'max:20'],
        ];
    }
}
