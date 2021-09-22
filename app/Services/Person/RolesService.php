<?php

namespace App\Services\Person;

use App\Enums\BaseAppEnum;
use App\Http\Resources\Roles\RolesResource;
use App\Repositories\RolesRepository;

class RolesService extends RolesRepository
{
    /**
     * Get all roles or find by id
     *
     * @param int|null $id
     * @return array
     */
    public function index(?int $id): array
    {
        if (is_null($id)) {
            $parentRoles = $this->newQuery()->whereNull('parent_id')->get();

            $roles = [];

            foreach ($parentRoles as $parentRole) {
                $roles[] = $parentRole;

                $roles = array_merge($roles, $this->newQuery()->where('parent_id', $parentRole->id)->get()->toArray());
            }

            return $roles;
        }

        return $this->newQuery()->where('parent_id', $id)->get()->toArray();
    }
}
