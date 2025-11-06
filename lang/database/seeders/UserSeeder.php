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
            'nama' => 'Naraya',
            'username' => 'nara666',
            'email' => 'rizthepublic666@gmail.com',
            'password' => Hash::make('nara666'),
            'created_at' => Now()
        ]);
        DB::table('users')->insert([
            'nama' => 'User 01',
            'username' => 'user666',
            'role' => 'user',
            'email' => 'user123@gmail.com',
            'password' => Hash::make('user123'),
            'created_at' => Now()
        ]);
    }
}
