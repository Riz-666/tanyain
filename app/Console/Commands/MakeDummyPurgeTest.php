<?php

namespace App\Console\Commands;

use App\Models\Artikel;
use App\Models\Draft;
use App\Models\FileRepo;
use App\Models\Repositori;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeDummyPurgeTest extends Command
{
    protected $signature = 'make:dummy-purge-test {--minutes=2}';
    protected $description = 'Bikin dummy data user/artikel/repo/draft yang expired dalam beberapa menit';

    public function handle()
    {
        $minutes   = $this->option('minutes');
        $expiredAt = now()->addMinutes($minutes);

        /*
        |--------------------------------------------------------------------------
        | User Dummy
        |--------------------------------------------------------------------------
        */
        $user = User::create([
            'nama'     => 'Dummy User Expired',
            'email'    => 'dummy' . time() . '@example.com',
            'password' => bcrypt('password'),
            'username' => 'dummyuser' . time(),
            'role'     => 'user',
            'foto'     => "dummy_user.png",
        ]);

        // Simpan foto dummy user di profile-trash
        Storage::disk('public')->put("profile-trash/dummy_user.png", 'gambar dummy user');

        // Tandai user soft delete + expired
        $user->delete();
        $user->deleted_until = $expiredAt;
        $user->save();

        $this->info("User dummy dibuat, expired $expiredAt");


        /*
        |--------------------------------------------------------------------------
        | Artikel Dummy
        |--------------------------------------------------------------------------
        */
        $artikel = Artikel::create([
            'user_id'       => $user->id,
            'repositori_id' => null,
            'judul'         => 'Dummy Artikel Expired',
            'slug'          => 'dummy-artikel-expired-' . time(),
            'isi'           => '<p><img src="/storage/trash/artikel/images/dummy_img.png"></p>',
            'file'          => 'dummy_artikel.txt',
            'views'         => 0,
            'status'        => 'private',
        ]);

        // Buat file di struktur trash artikel (images/files/cover)
        Storage::disk('public')->put("trash/artikel/{$artikel->id}/files/dummy_artikel.txt", 'isi dummy artikel');
        Storage::disk('public')->put("trash/artikel/{$artikel->id}/images/dummy_img.png", 'gambar dummy artikel');
        Storage::disk('public')->put("trash/artikel/{$artikel->id}/cover/dummy_cover.png", 'cover dummy artikel');

        // Draft dummy
        $draft = Draft::create([
            'user_id'    => $user->id,
            'artikel_id' => $artikel->id,
            'judul'      => 'Draft Dummy',
            'tags'       => json_encode(['Laravel', 'Model']),
            'content'    => '<p>draft dummy</p>',
            'expires_at' => $expiredAt,
        ]);

        // Simpan file temp draft
        Storage::disk('public')->put("artikel/temp/dummy_draft.txt", 'isi dummy draft');

        // Tandai artikel sudah dihapus (soft delete + set expired)
        $artikel->delete();
        $artikel->deleted_until = $expiredAt;
        $artikel->save();

        $this->info("Artikel dummy + draft dibuat, expired $expiredAt");


        /*
        |--------------------------------------------------------------------------
        | Repo Dummy
        |--------------------------------------------------------------------------
        */
        $repo = Repositori::create([
            'user_id'    => $user->id,
            'judul_repo' => 'Dummy Repo Expired',
            'deskripsi'  => 'repo testing',
            'status'     => 'publik',
        ]);

        // Buat file repo di folder trash
        Storage::disk('public')->put("repositori/trash/dummy_repo.csv", 'isi dummy repo');

        $fileRepo = FileRepo::create([
            'repositori_id' => $repo->id,
            'nama_file'     => 'dummy_repo.csv',
            'path'          => "repositori/trash/dummy_repo.csv",
            'ekstensi'      => 'csv',
            'ukuran'        => 370,
        ]);

        // Tandai repo sudah dihapus (soft delete + set expired)
        $repo->delete();
        $repo->deleted_until = $expiredAt;
        $repo->save();

        // Tandai file repo juga expired
        $fileRepo->delete();
        $fileRepo->deleted_until = $expiredAt;
        $fileRepo->save();

        $this->info("Repo dummy + file_repo dibuat, expired $expiredAt");
    }
}
