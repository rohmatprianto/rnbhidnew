<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'User One',
                'email' => 'user1@local.test',
                'password' => 'User12345!',
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@local.test',
                'password' => 'User12345!',
            ],
            [
                'name' => 'User Three',
                'email' => 'user3@local.test',
                'password' => 'User12345!',
            ],
            [
                'name' => 'User Four',
                'email' => 'user4@local.test',
                'password' => 'User12345!',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    // kalau kolom is_admin ada:
                    'is_admin' => false,
                ]
            );
        }
    }
}
