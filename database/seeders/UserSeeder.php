<?php

namespace Database\Seeders;

use App\Models\User;
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
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $allPermissions = Permission::pluck('name')->toArray();
        $superAdminRole->syncPermissions($allPermissions);

        $superAdmin = User::firstOrCreate([
            'email' => 'rendy@gmail.com',
        ], [
            'name' => 'Rendy',
            'password' => Hash::make('qawsedrf'),
            'email_verified_at' => now(),
            'is_active' => 1,
        ]);

        $superAdmin->assignRole($superAdminRole);

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $adminPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'users.restore',
            'users.forceDelete',
        ])->get()->pluck('name')->toArray();

        $adminRole->syncPermissions($adminPermissions);

        $adminUser = User::firstOrCreate([
            'email' => 'edo@gmail.com',
        ], [
            'name' => 'Edo',
            'password' => Hash::make('qawsedrf'),
            'email_verified_at' => now(),
            'is_active' => 1,
        ]);

        $adminUser->assignRole($adminRole);
    }
}
