<?php

namespace App\Http\Requests\Events;

use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;

class ApplaudRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'min:1', 'max:10']
        ];
    }
}
