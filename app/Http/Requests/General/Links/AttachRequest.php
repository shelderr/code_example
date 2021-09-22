<?php

namespace App\Http\Requests\General\Links;

use App\Enums\SocialLinksEnum;
use App\Models\Details;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_id'   => ['required', 'integer', 'min:1'],
            'target_type' => ['required', 'string', Rule::in(Details::ALLOWED_TYPES)],
            'url'         => ['required_without:description', 'string', 'regex:' . SocialLinksEnum::WEB_URL],
            'description' => ['required_without:url', 'string', 'min:2', 'max:2048'],
        ];
    }
}
