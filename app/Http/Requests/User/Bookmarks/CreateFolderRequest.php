<?php

namespace App\Http\Requests\User\Bookmarks;

use App\Services\Base\BaseAppGuards;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('bookmark_folder', 'name')->where(
                    function (Builder $query) {
                        $query->where('user_id', '=', auth()->guard(BaseAppGuards::USER)->user()->id);
                    }
                ),
            ],
        ];
    }
}
