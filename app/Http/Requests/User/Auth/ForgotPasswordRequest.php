<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\APIFormRequest;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ];
    }
}
