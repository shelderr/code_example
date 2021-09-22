<?php

namespace App\Http\Requests\Admin\Management\Persons;

use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;

class PersonIndexRequest extends FormRequest
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
            'paginate' => ['integer', 'min:2', 'max:20']
        ];
    }
}
