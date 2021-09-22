<?php

namespace App\Rules\Persons;

use Illuminate\Contracts\Validation\Rule;

class DeathDateRule implements Rule
{
    private ?bool $isDead;

    /**
     * Create a new rule instance.
     *
     * @param bool|null $isDead
     */
    public function __construct(?bool $isDead)
    {
        $this->isDead = $isDead;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param        $date
     *
     * @return bool
     */
    public function passes($attribute, $date)
    {
        if (! $this->isDead && isset($date)) {
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
        return 'Field not acceptable when is_deceased field is false';
    }
}
