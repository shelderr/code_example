<?php

namespace App\Http\Requests\User\Settings;

use App\Models\User;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePhotoRequest extends FormRequest
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
            'photo' => [
                'file',
                'mimes:png,jpg, jpeg',
                'max:2048',
            ],
        ];
    }

    public function messages()
    {
        return [
            'photo.max' => 'The document may not be greater than _10 megabytes'
        ];
    }
}
