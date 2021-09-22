<?php

namespace App\Http\Requests\User\Settings;

use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUsernameRequest extends FormRequest
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
            'user_name' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'unique:users,user_name',
                'regex:' . User::USERNAME_REGEX,
            ],
        ];
    }
}
