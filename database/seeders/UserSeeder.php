<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nama' => 'Super Admin',
            'username' => '01admin',
            'email' => 'administrator@gmail.com',
            'password' => Hash::make('Admin123')
        ]);
        DB::table('users')->insert([
            'nama' => 'User 01',
            'username' => 'user666',
            'role' => 'user',
            'email' => 'user123@gmail.com',
            'password' => Hash::make('user123')
        ]);
    }
}
