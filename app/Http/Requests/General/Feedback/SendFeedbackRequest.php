<?php

namespace App\Http\Requests\General\Feedback;

use App\Enums\BaseAppEnum;
use App\Enums\SocialLinksEnum;
use App\Models\Feedback;
use App\Services\Base\BaseAppGuards;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendFeedbackRequest extends FormRequest
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
            'target_id'   => ['required_with:target_type', 'integer', 'min:1'],
            'target_type' => ['required_with:target_id', 'string', Rule::in(Feedback::$entityTypes)],
            'type'        => ['required', 'string', Rule::in(Feedback::$types)],
            'subject'     => ['required', 'string', 'min:2', 'max:255'],
            'message'     => ['required', 'string', 'min:2'],
            'images'      => ['array', 'min:1', 'max:10'],
            'images.*'    => [
                'required_with:images',
                'file',
                'mimes:png,jpg,jpeg,csv,xslx,xls,docx,doc,txt',
                'max:' . BaseAppEnum::DEFAULT_IMAGE_MAX_SIZE,
            ],
            'links'       => ['array'],
            'links.*'     => ['string', 'regex:' . SocialLinksEnum::WEB_URL, 'min:2', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'images.*.max' => 'The document may not be greater than _10 megabytes',
        ];
    }
}
