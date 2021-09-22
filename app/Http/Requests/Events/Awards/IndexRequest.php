<?php

namespace App\Http\Requests\Events\Awards;

use App\Models\Events\Awards;
use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('events', 'id')->where('type', Event::TYPE_EVENT),
            ],
            'type' => ['required', Rule::in(Awards::getTypes())],
            'paginate' => ['integer', 'min:2', 'max:20'],
        ];
    }
}
