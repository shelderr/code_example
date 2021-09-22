<?php

namespace App\Rules\Persons;

use App\Exceptions\Http\BadRequestException;
use App\Models\Roles;
use Illuminate\Contracts\Validation\Rule;

class AttachPersonRole implements Rule
{
    private $errors = [];

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
     * @param array  $ids
     *
     * @return bool
     * @throws \App\Exceptions\Http\BadRequestException
     */
    public function passes($attribute, $ids): bool
    {
        if (! isArrayInt($ids)) {
              throw new BadRequestException('Ids list must be integer');
        }

        $roles = Roles::findOrFail($ids);

        foreach ($roles as $role) {
            if (is_null($role->parent_id)) {
                return false;
            }
        }


        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'root role not available for select';
    }
}
