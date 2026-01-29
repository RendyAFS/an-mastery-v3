<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnotherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 100;

        for ($i = 1; $i <= $total; $i++) {
            User::firstOrCreate(
                [
                    'email' => "user{$i}@gmail.com",
                ],
                [
                    'name'              => "User {$i}",
                    'password'          => Hash::make('qawsedrf'),
                    'email_verified_at' => now(),
                    'is_active'         => false,
                ]
            );
        }
    }
}
