<?php

namespace App\Http\Requests\General;

use App\Models\Category;
use App\Models\Events\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', 'string', 'bail', Rule::in(Category::ALL_CATEGORIES)],
        ];
    }
}
