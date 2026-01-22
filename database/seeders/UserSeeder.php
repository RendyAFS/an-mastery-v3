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
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::pluck('name')->toArray();

        $role->syncPermissions($permissions);

        $user = User::firstOrCreate(
            [
                'email' => 'rendy@gmail.com',
                'email_verified_at' => now(),
                'name'              => 'Rendy',
                'password'          => Hash::make('qawsedrf'),
                'is_active'         => 1,
            ]
        );

        $user->assignRole($role);
    }
}
