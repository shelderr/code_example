<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\APIFormRequest;
use App\Models\Admin;
use App\Models\User;
use Auth;

class ResetPasswordRequest extends APIFormRequest
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
    public function rules(): array
    {
        return [
            'password' => ['required', 'regex:' . USER::PASSWORD_REGEX, 'confirmed'],
            'token'    => ['required', 'exists:password_resets,token'],

        ];
    }
}
