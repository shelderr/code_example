<?php

namespace App\Http\Requests\Events\Media;

use App\Models\Media;
use App\Rules\Media\VideoUrlRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachVideoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id'   => ['required', 'integer', 'min:1', 'bail', Rule::exists('events', 'id')],
            'type'       => ['required', 'string', Rule::in([Media::TYPE_VIDEOS])],
            'url'        => ['required', 'string', new VideoUrlRule()]
        ];
    }
}
