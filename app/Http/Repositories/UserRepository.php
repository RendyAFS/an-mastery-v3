<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::with('roles')->get();
    }
}
