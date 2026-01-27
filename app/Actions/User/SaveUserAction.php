<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SaveUserAction
{
    public function execute(array $data, ?User $user = null): User
    {
        return DB::transaction(function () use ($data, $user) {

            $roleIds = [];

            if (isset($data['roles']) && $data['roles'] !== null) {
                $roleIds = is_array($data['roles'])
                    ? $data['roles']
                    : [$data['roles']];
            }

            unset($data['roles']);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($user) {
                $user->update($data);
            } else {
                $user = User::create($data);
            }

            $roles = Role::whereIn('id', $roleIds)->get();

            $user->syncRoles($roles);

            return $user->load('roles');
        });
    }
}
