<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Repositori;
use Illuminate\Support\Facades\Storage;

class PurgeOldRepositori extends Command
{
    protected $signature = 'repositori:purge';
    protected $description = 'Hapus repositori yang sudah lewat 20 hari di trash + hapus file yang bersangkutan';

    public function handle()
    {
        $this->info('Memulai pembersihan repositori expired...');

        $repos = Repositori::onlyTrashed()
            ->where('deleted_until', '<', now())
            ->get();

        $count = 0;

        foreach ($repos as $repo) {
            // Hapus file-file terkait repo ini saja
            foreach ($repo->fileRepo()->withTrashed()->get() as $file) {
                if ($file->path && Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
                $file->forceDelete(); // hapus record file
            }

            // Hapus record repositori
            $repo->forceDelete();
            $count++;
        }

        $this->info("✅ {$count} repositori expired berhasil dibersihkan.");
    }
}
