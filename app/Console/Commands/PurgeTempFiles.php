<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Draft;

class PurgeTempFiles extends Command
{
    protected $signature = 'temp:purge';
    protected $description = 'Hapus file temp di artikel/temp yang tidak terkait draft aktif dan lebih dari 24 jam';

    public function handle()
    {
        $this->info('Memulai pembersihan file temp > 24 jam yang tidak terkait draft aktif...');

        $allFiles = Storage::disk('public')->allFiles('artikel/temp');
        $deletedCount = 0;

        foreach ($allFiles as $file) {
            // ambil draft_id dari path: artikel/temp/{draft_id}/nama_file
            $segments = explode('/', $file);
            if (count($segments) < 3) continue; // safety check
            $draftId = $segments[2];

            $draft = Draft::find($draftId);

            // skip file kalau masih ada draft aktif
            if ($draft && $draft->expires_at->gt(now())) {
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            // hapus kalau lebih dari 24 jam
            if ($lastModified->lt(now()->subHours(24))) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $this->info("Deleted: {$file}");
            }
        }

        $this->info("✅ Selesai. {$deletedCount} file temp dihapus dari artikel/temp.");
    }
}
