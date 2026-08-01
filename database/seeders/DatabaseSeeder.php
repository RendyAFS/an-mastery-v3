<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuPermissionSeeder::class,
            // AnotherUserSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            SupplierSeeder::class,
            ImageFabricSeeder::class,
        ]);
    }
}
