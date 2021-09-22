<?php

namespace App\Http\Requests\User\Bookmarks;

use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;

class SearchFolderRequest extends FormRequest
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
            'search'   => ['required', 'string', 'min:2', 'max:255'],
            'paginate' => ['integer', 'min:2', 'max:20'],
        ];
    }
}
