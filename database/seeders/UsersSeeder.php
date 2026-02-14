<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder{

    public function run(): void
    {
        User::create([
        'name' => 'User',
        'email' => 'user@gmail.com',
        'password' => 'user', 
        'state_admin' => '0'
    ]);

    User::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => 'admin',
        'state_admin' => '1'
    ]);
    }
}
