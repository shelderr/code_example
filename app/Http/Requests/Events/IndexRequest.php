<?php

namespace App\Http\Requests\Events;

use App\Models\Category;
use App\Models\Collective;
use App\Models\Events\Event;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Request $request)
    {
        return [
            'type'                            => ['required', 'string', Rule::in(Event::ALL_EVENTS)],
            'paginate'                        => ['integer', 'min:2', 'max:22'],
            'sorting.year_established'        => ['array'],
            'sorting.year_established.min'    => [
                'required_with:sorting.year_established',
                'integer',
                'digits:4',
                'bail',
                'lt:sorting.year_established.max',
            ],
            'sorting.year_established.max'    => [
                'required_with:sorting.year_established',
                'integer',
                'digits:4',
                'bail',
                'gt:sorting.year_established.min',
            ],
            'sorting.is_active'               => ['boolean'],
            'sorting.with_photo'              => ['boolean'],
            'sorting.show_type_ids'           => ['array'],
            'sorting.show_type_ids.*'         => [
                'integer',
                'min:1',
                'bail',
                Rule::exists('categories', 'id')->where(
                    function (Builder $q) {
                        $q->where('type', '=', Category::SHOW_TYPE)
                            ->orWhere('type', '=', Category::EVENT_TYPE);
                    }
                ),
            ],
            'sorting.country_created_ids'     => ['array'],
            'sorting.country_created_ids.*'   => [
                'required',
                'integer',
                'min:1',
                Rule::exists('countries', 'id'),
            ],
            'sorting.country_presented_ids'   => ['array'],
            'sorting.country_presented_ids.*' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('countries', 'id'),
            ],
            'sorting.language_ids'            => ['array'],
            'sorting.language_ids.*'          => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('languages', 'id'),
            ],
            'sorting.production_type_ids'     => ['array'],
            'sorting.production_type_ids.*'   => [
                'required',
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('categories', 'id')->where(
                    function (Builder $q) {
                        $q->where('type', '=', Category::PRODUCTION_TYPE);
                    }
                ),
            ],
            'sorting.show_audience_ids'       => ['array'],
            'sorting.show_audience_ids.*'     => [
                'integer',
                'min:1',
                'distinct',
                'bail',
                Rule::exists('categories', 'id')->where(
                    function (Builder $q) {
                        $q->where('type', '=', Category::SHOW_AUDIENCE_TYPE);
                    }
                ),
            ],
            'sorting.rating'                  => ['string', Rule::in(Event::$sortDir)],
            'sorting.alphabetical'            => ['string', 'regex:' . Event::ALPHABETICAL_SORTING_REGEX],
            'sorting.order'                   => ['string', Rule::in(Collective::$sortDir)],
        ];
    }
}
