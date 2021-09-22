<?php

namespace App\Http\Requests\General\Links;

use App\Enums\SocialLinksEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'url'         => ['required', 'string', 'regex:'. SocialLinksEnum::WEB_URL],
        ];
    }
}
