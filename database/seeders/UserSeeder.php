<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user admin creado
        User::create([
            'name' => 'Forlan',
            'username' => 'admin',
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'role' => 'admin'
        ]);

        // user normal creado
        User::create([
            'name' => 'forlan user',
            'username' => 'user',
            'password' => Hash::make(env('USER_PASSWORD')),
            'role' => 'user'
        ]);
    }
}
