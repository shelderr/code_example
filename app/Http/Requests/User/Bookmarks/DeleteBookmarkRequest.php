<?php

namespace App\Http\Requests\User\Bookmarks;

use App\Models\User\Bookmarks\BookmarkFolder;
use App\Services\Base\BaseAppGuards;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeleteBookmarkRequest extends FormRequest
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
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request)
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
            'entity_id'  => [
                'required',
                'integer',
                'min:1'
            ],
            'type' => ['required', 'string', Rule::in(BookmarkFolder::$allTypes)]
        ];
    }
}
