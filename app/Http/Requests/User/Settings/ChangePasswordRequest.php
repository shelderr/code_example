<?php

namespace App\Http\Requests\User\Settings;

use App\Http\Rules\PasswordRule;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function authorize(): \Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::guard(BaseAppGuards::USER)->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth(BaseAppGuards::USER)->user();

        return [
            'current_password' => [
                'required', 'string',
                'min:8', 'regex:' . User::PASSWORD_REGEX,
                new PasswordRule($user->password)
            ],
            'new_password'     => ['required', 'string', 'min:8', 'regex:' . User::PASSWORD_REGEX, 'confirmed'],
        ];
    }
}
