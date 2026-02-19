<?php

namespace App\Http\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAll()
    {
        return Role::all();
    }
}
