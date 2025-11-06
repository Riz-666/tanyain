<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\ArtikelTagSeeder;
use Database\Seeders\DummySeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // DummySeeder::class,
            TagSeeder::class,
            // ArtikelTagSeeder::class,
            // SaranSeeder::class,

        ]);
    }
}
