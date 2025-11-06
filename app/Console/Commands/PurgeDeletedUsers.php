<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PurgeDeletedUsers extends Command
{
    protected $signature = 'users:purge';
    protected $description = 'Permanently delete expired users. Artikel/Repo yang restored -> user_id null.';

    public function handle()
    {
        $now = Carbon::now();
        $this->info('Memulai pembersihan user expired...');

        $users = User::withTrashed()
            ->with(['artikel', 'repositori.fileRepo'])
            ->whereNotNull('deleted_until')
            ->where('deleted_until', '<=', $now)
            ->get();

        $count = 0;

        foreach ($users as $user) {
            // === ARTIKEL ===
            foreach ($user->artikel()->withTrashed()->get() as $artikel) {
                if ($artikel->trashed()) {
                    // Hapus permanen + file trash
                    $trashFolder = "trash/artikel/{$artikel->id}";

                    if ($artikel->file && Storage::disk('public')->exists("{$trashFolder}/files/{$artikel->file}")) {
                        Storage::disk('public')->delete("{$trashFolder}/files/{$artikel->file}");
                    }

                    if ($artikel->cover && Storage::disk('public')->exists("{$trashFolder}/cover/{$artikel->cover}")) {
                        Storage::disk('public')->delete("{$trashFolder}/cover/{$artikel->cover}");
                    }

                    if (!empty($artikel->isi)) {
                        preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
                        foreach ($matches[1] as $imgUrl) {
                            $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                            $trashPath = "trash/{$path}";
                            if (Storage::disk('public')->exists($trashPath)) {
                                Storage::disk('public')->delete($trashPath);
                            }
                        }
                    }

                    if (Storage::disk('public')->exists($trashFolder)) {
                        Storage::disk('public')->deleteDirectory($trashFolder);
                    }

                    $artikel->forceDelete();
                } else {
                    // Artikel sudah direstore → null-in user_id
                    $artikel->update(['user_id' => null]);
                }
            }

            // === REPOSITORI ===
            foreach ($user->repositori()->withTrashed()->get() as $repo) {
                if ($repo->trashed()) {
                    // Hapus permanen file repo
                    foreach ($repo->fileRepo()->withTrashed()->get() as $file) {
                        $trashFileName = $repo->id . '_' . $file->nama_file;
                        $trashPath = "repositori/trash/{$trashFileName}";
                        if (Storage::disk('public')->exists($trashPath)) {
                            Storage::disk('public')->delete($trashPath);
                        }
                    }

                    $repo->fileRepo()->withTrashed()->forceDelete();
                    $repo->forceDelete();
                } else {
                    // Repo sudah direstore → null-in user_id
                    $repo->update(['user_id' => null]);
                }
            }

            // === FOTO PROFIL ===
            if (!empty($user->foto) && Storage::disk('public')->exists("profile-trash/{$user->foto}")) {
                Storage::disk('public')->delete("profile-trash/{$user->foto}");
            }

            // === HAPUS USER ===
            $user->forceDelete();
            $count++;

            $this->info("User {$user->nama} permanently deleted (restored content diselamatkan).");
        }

        $this->info(" {$count} user expired berhasil dibersihkan.");
        return 0;
    }
}
