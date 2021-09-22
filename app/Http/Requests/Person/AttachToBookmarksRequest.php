<?php

namespace App\Http\Requests\Person;

use App\Services\Base\BaseAppGuards;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachToBookmarksRequest extends FormRequest
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
            'folder_id' => [
                'required',
                'integer',
                'bail',
                Rule::exists('bookmark_folder', 'id')->where(
                    function (Builder $q) {
                        $q->where('user_id', '=', auth()->guard(BaseAppGuards::USER)->user()->id);
                    }
                ),
            ],
            'person_id'  => ['required', 'integer', 'bail', Rule::exists('persons', 'id')],
        ];
    }
}
