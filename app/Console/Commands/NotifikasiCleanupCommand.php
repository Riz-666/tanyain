<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class NotifikasiCleanupCommand extends Command
{
    protected $signature = 'notifikasi:cleanup';
    protected $description = 'Hapus notifikasi lama jika melebihi 100 per user';

    public function handle()
    {
        // Ambil semua user yang punya notifikasi
        $userIds = Notifikasi::distinct()->pluck('user_id');

        $totalDeleted = 0;

        foreach ($userIds as $userId) {
            // Ambil ID notifikasi yang harus dihapus (lebih dari 100, urut dari terlama)
            $idsToDelete = Notifikasi::where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->skip(100) // Lewati 100 notifikasi terbaru
                ->take(PHP_INT_MAX)
                ->pluck('id');

            if ($idsToDelete->isNotEmpty()) {
                $deleted = Notifikasi::whereIn('id', $idsToDelete)->delete();
                $totalDeleted += $deleted;

                $this->info("User ID {$userId}: dihapus {$deleted} notifikasi lama.");
            }
        }

        $this->info(" Total notifikasi lama yang dihapus: {$totalDeleted}");
        return 0;
    }
}
