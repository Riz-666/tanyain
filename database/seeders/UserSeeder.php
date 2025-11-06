<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
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
            'nama' => 'Administrator',
            'username' => 'administrator-01',
            'email' => 'administrator@gmail.com',
            'password' => Hash::make('@Admin123'),
            'role' => 'super_admin',
            'created_at' => Now()
        ]);

        DB::table('users')->insert([
            'nama' => 'Nara',
            'username' => 'nara-100303',
            'email' => 'nara@gmail.com',
            'password' => Hash::make('@Devangga666'),
            'role' => 'user',
            'created_at' => Now()
        ]);

        DB::table('users')->insert([
            'nama' => 'Devangga Rizki Naraya',
            'username' => 'riz-100303',
            'email' => 'narayariz666@gmail.com',
            'instagram' => 'https://www.instagram.com/devangga.rizki/',
            'linkedin' => 'https://www.linkedin.com/in/devangga-rizki-naraya-473a942b0/',
            'github' => 'https://github.com/Riz-666',
            'bio' => 'Never Be One',
            'password' => Hash::make('@Devangga.rizki666'),
            'role' => 'user',
            'created_at' => Now()
        ]);

        // $faker = Faker::create();

        // for ($i = 1; $i <= 8; $i++) {
        //     User::create([
        //         'nama' => $faker->name,
        //         'email' => $faker->unique()->safeEmail,
        //         'username' => $faker->unique()->userName,
        //         'password' => Hash::make($i), // password 1-8
        //         'role' => 'user',
        //         'foto' => null,
        //         'linkedin' => null,
        //         'instagram' => null,
        //         'github' => null,
        //         'bio' => $faker->sentence,
        //     ]);
        // }
    }
}
