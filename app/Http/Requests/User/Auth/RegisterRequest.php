<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Rules\Recaptcha;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                     => [
                'required',
                'max:255',
                'email:rfc,dns',
                'unique:users',
                'regex:' . User::EMAIL_REGEX,
            ],
            'password'                  => [
                'required',
                'string',
                'min:8',
                'regex:' . User::PASSWORD_REGEX,
                'confirmed',
            ],
            'user_name'                 => [
                'required',
                'string',
                'min:2',
                'max:60',
                'unique:users,user_name',
                'regex:' . User::USERNAME_REGEX,
            ],
            'google_recaptcha_response' => ['required', new Recaptcha()],
            'news_subscription'         => ['required', 'boolean'],
        ];
    }
}
