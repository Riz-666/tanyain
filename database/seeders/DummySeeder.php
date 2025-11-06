<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\User;
use App\Models\viewArtikel;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        // === 1. Pastikan ada user non-admin (ID != 1) ===
        $adminExists = User::where('id', 1)->exists();
        if (!$adminExists) {
            // Buat admin dulu (opsional, tapi disarankan)
            User::insert([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => \Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buat 10 user dummy (akan jadi ID 2–11)
        User::factory(10)->create();

        // Ambil hanya user non-admin
        $userIds = User::where('id', '!=', 1)->pluck('id')->toArray();

        // === 2. Buat 200 repositori ===
        $repoData = [];
        foreach (range(1, 200) as $i) {
            $status = rand(0, 1) ? 'publik' : 'private';
            $repo = Repositori::factory()->create([
                'user_id' => $userIds[array_rand($userIds)],
                'status' => $status,
            ]);
            $repoData[$repo->id] = $status;
        }

        $repoIds = array_keys($repoData);

        // === 3. Buat 200 artikel ===
        $artikelIds = [];
        foreach (range(1, 200) as $i) {
            $rid = $repoIds[array_rand($repoIds)];
            $artikel = Artikel::factory()->create([
                'user_id' => $userIds[array_rand($userIds)],
                'repositori_id' => $rid,
                'status' => $repoData[$rid],
            ]);
            $artikelIds[] = $artikel->id;
        }

        // === 4. Buat view artikel ===
        foreach ($artikelIds as $artikelId) {
            // Setiap artikel punya 0–50 view
            $viewCount = rand(0, 50);

            for ($j = 0; $j < $viewCount; $j++) {
                viewArtikel::factory()->create([
                    'artikel_id' => $artikelId,
                    'user_id' => $userIds[array_rand($userIds)], // user acak (bukan admin)
                ]);
            }
        }
    }
}
