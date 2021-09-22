<?php

namespace App\Http\Requests\Admin\Management\Users\Verification;

use App\Models\Persons;
use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function authorize()
    {
        return auth()->guard(BaseAppGuards::ADMIN)->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'status'  => [
                'required',
                'string',
                Rule::in(
                    [
                        User::LINK_STATUS_REJECTED,
                        User::LINK_STATUS_ACCEPTED,
                    ]
                ),
            ],
        ];
    }
}
