<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class PurgeOldArticles extends Command
{
    protected $signature = 'artikel:purge';
    protected $description = 'Hapus artikel yang sudah lewat masa 20 hari di trash + bersihkan file-nya';

    public function handle()
    {
        $this->info('Memulai pembersihan artikel expired...');

        $artikels = Artikel::onlyTrashed()
            ->where('deleted_until', '<', now())
            ->get();

        $count = 0;

        foreach ($artikels as $artikel) {
            $trashFolder = "trash/artikel/{$artikel->id}";

            // ✅ Hapus file utama
            if ($artikel->file && Storage::disk('public')->exists("{$trashFolder}/files/{$artikel->file}")) {
                Storage::disk('public')->delete("{$trashFolder}/files/{$artikel->file}");
            }

            // ✅ Hapus cover
            if ($artikel->cover && Storage::disk('public')->exists("{$trashFolder}/cover/{$artikel->cover}")) {
                Storage::disk('public')->delete("{$trashFolder}/cover/{$artikel->cover}");
            }

            // ✅ Hapus gambar dari isi artikel
            if (!empty($artikel->isi)) {
                preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
                foreach ($matches[1] as $imgUrl) {
                    $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            // ✅ Hapus seluruh folder trash artikel
            if (Storage::disk('public')->exists($trashFolder)) {
                Storage::disk('public')->deleteDirectory($trashFolder);
            }

            // ✅ Hapus record dari database
            $artikel->forceDelete();
            $count++;
        }

        \Log::info("Cronjob purge artikel dijalankan: " . now() . " | Jumlah dihapus: {$count}");

        $this->info("✅ {$count} artikel expired berhasil dibersihkan.");
    }
}
