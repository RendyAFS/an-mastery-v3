<?php

namespace App\Http\Repositories;

use App\Models\Role;
use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::with('roles')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getRoles()
    {
        return Role::orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
    }
}
