<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Saran;
use App\Models\User;
use Faker\Factory as Faker;

class SaranSeeder extends Seeder
{
    public function run()
    {
        // $faker = Faker::create();

        // // Ambil semua user id untuk dijadikan foreign key
        // $userIds = User::pluck('id')->toArray();

        // for ($i = 1; $i <= 20; $i++) {
        //     Saran::create([
        //         'user_id' => $faker->randomElement($userIds), // pilih user random
        //         'nama' => $faker->name, // nama random
        //         'pesan' => 'Ini adalah contoh pesan saran nomor ' . $i,
        //         'ip_address' => $faker->ipv4, // IP address random
        //         'created_at' => now()->subDays(rand(0, 10)), // tanggal acak 10 hari terakhir
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
