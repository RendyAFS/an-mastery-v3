<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::pluck('name')->toArray();

        $role->syncPermissions($permissions);

        $user = User::firstOrCreate(
            [
                'email' => 'rendy@gmail.com',
            ],
            [
                'name' => 'Rendy',
                'password' => Hash::make('qawsedrf'),
            ]
        );

        $user->assignRole($role);
    }
}
