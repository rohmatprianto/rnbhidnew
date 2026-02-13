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
  ['name' => 'User Five',     'email' => 'user5@local.test',  'password' => 'User12345!'],
  ['name' => 'User Six',      'email' => 'user6@local.test',  'password' => 'User12345!'],
  ['name' => 'User Seven',    'email' => 'user7@local.test',  'password' => 'User12345!'],
  ['name' => 'User Eight',    'email' => 'user8@local.test',  'password' => 'User12345!'],
  ['name' => 'User Nine',     'email' => 'user9@local.test',  'password' => 'User12345!'],
  ['name' => 'User Ten',      'email' => 'user10@local.test', 'password' => 'User12345!'],
  ['name' => 'User Eleven',   'email' => 'user11@local.test', 'password' => 'User12345!'],
  ['name' => 'User Twelve',   'email' => 'user12@local.test', 'password' => 'User12345!'],
  ['name' => 'User Thirteen', 'email' => 'user13@local.test', 'password' => 'User12345!'],
  ['name' => 'User Fourteen', 'email' => 'user14@local.test', 'password' => 'User12345!'],
  ['name' => 'User Fifteen',  'email' => 'user15@local.test', 'password' => 'User12345!'],
  ['name' => 'User Sixteen',  'email' => 'user16@local.test', 'password' => 'User12345!'],
  ['name' => 'User Seventeen','email' => 'user17@local.test', 'password' => 'User12345!'],
  ['name' => 'User Eighteen', 'email' => 'user18@local.test', 'password' => 'User12345!'],
  ['name' => 'User Nineteen', 'email' => 'user19@local.test', 'password' => 'User12345!'],
  ['name' => 'User Twenty',   'email' => 'user20@local.test', 'password' => 'User12345!'],
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
