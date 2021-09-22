<?php

namespace App\Http\Requests\User\Settings\Personality;

use App\Enums\SocialLinksEnum;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function authorize()
    {
        return auth()->guard(BaseAppGuards::USER)->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'person_id'    => ['required', 'integer', Rule::exists('persons', 'id')],
            'facebook_url' => ['required', 'string', 'min:2', 'max:512', 'regex:' . SocialLinksEnum::FACEBOOK_REGEX],
        ];
    }
}
