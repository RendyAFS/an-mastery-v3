<?php

namespace App\Http\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function getAll(): Collection
    {
        return Role::query()
            ->where('name', '!=', 'Super Admin')
            ->get();
    }
}
