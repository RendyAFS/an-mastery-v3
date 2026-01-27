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
        $users = [
            [
                'name' => 'User 1',
                'email' => 'user1@gmail.com',
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@gmail.com',
            ],
            [
                'name' => 'User 3',
                'email' => 'user3@gmail.com',
            ],
            [
                'name' => 'User 4',
                'email' => 'user4@gmail.com',
            ],
            [
                'name' => 'User 5',
                'email' => 'user5@gmail.com',
            ],
            [
                'name' => 'User 6',
                'email' => 'user6@gmail.com',
            ],
            [
                'name' => 'User 7',
                'email' => 'user7@gmail.com',
            ],
            [
                'name' => 'User 8',
                'email' => 'user8@gmail.com',
            ],
            [
                'name' => 'User 9',
                'email' => 'user9@gmail.com',
            ],
            [
                'name' => 'User 10',
                'email' => 'user10@gmail.com',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('qawsedrf'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
        }
    }
}
