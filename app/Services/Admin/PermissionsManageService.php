<?php

namespace App\Services\Admin;

use App\Models\Admin\Permissions;

class PermissionsManageService
{
    public function permissions()
    {
        return  Permissions::all()->groupBy('entity');
    }
}
