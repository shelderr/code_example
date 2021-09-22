<?php

namespace App\Rules\Event;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class EndDateRule implements Rule
{
    private ?bool $isActive;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $endDate
     *
     * @return bool
     */
    public function passes($attribute, $endDate): bool
    {
        $diff = Carbon::parse($endDate)->diffInDays(Carbon::now());

        if ($this->isActive && $diff < 0) {
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
        return 'End date cannot be less than the present while event is active';
    }
}
