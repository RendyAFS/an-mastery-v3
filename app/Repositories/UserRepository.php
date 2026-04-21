<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;

class UserRepository
{
    public function getAll($filter = 'active')
    {
        $query = User::with('roles')->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }

    public function getRoles()
    {
        return Role::orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
    }
}
