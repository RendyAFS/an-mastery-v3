<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAll($filter = 'active')
    {
        $query = Role::query()
            ->withCount('users')
            ->where('name', '!=', 'Super Admin')
            ->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
