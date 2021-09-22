<?php

namespace App\Rules\Collective;

use Illuminate\Contracts\Validation\Rule;

class YearsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $year
     *
     * @return bool
     */
    public function passes($attribute, $year)
    {
        if ((int) $year > (int) date('Y')) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The year cant be bigger than the present";
    }
}
