<?php

namespace App\Rules\Venue;

use Illuminate\Contracts\Validation\Rule;

class CoordinatesRule implements Rule
{

    private string $latitudeRegex = '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'; //широта

    private string $longitudeRegex = '/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'; //долгота

    private string $validationMsg = 'Coordinates check failed';

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
     * @param        $coordinates
     *
     * @return bool
     */
    public function passes($attribute, $coordinates): bool
    {
        $coordinates = explode(',', $coordinates);

        if (count($coordinates) > 2 || count($coordinates) < 2) {
            $this->validationMsg = 'wrong coordinates type';
        }

        $latitude  = (float) trim($coordinates[0]);
        $longitude = (float) trim($coordinates[1]);


        if (! preg_match($this->latitudeRegex, $latitude)) {
            $this->validationMsg = 'Wrong latitude';

            return false;
        }

        if (! preg_match($this->longitudeRegex, $longitude)) {
            $this->validationMsg = 'Wrong longitude';

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->validationMsg;
    }
}
