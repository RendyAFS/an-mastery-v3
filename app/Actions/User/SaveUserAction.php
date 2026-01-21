<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaveUserAction
{
    public function execute(array $data, ?User $user = null): User
    {
        return DB::transaction(function () use ($data, $user) {
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if ($user) {
                $user->update($data);
            } else {
                $user = User::create($data);
            }

            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            return $user->load('roles');
        });
    }
}
