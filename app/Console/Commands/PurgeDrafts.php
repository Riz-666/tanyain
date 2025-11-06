<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Draft;
use Illuminate\Support\Facades\Storage;

class PurgeDrafts extends Command
{
    protected $signature = 'drafts:purge';
    protected $description = 'Hapus drafts expired + hapus file temp yang terkait';

    public function handle()
    {
        $this->info('Memulai pembersihan draft expired...');

        $expiredDrafts = Draft::where('expires_at', '<', now())->get();
        $draftCount = $expiredDrafts->count();
        $fileCount  = 0;

        foreach ($expiredDrafts as $draft) {
            // Hapus file temp terkait draft ini
            $tempFolder = "artikel/temp/{$draft->id}";
            if (Storage::disk('public')->exists($tempFolder)) {
                $files = Storage::disk('public')->files($tempFolder);
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                    $fileCount++;
                }
                // Jangan hapus foldernya
            }

            // Hapus record draft langsung (permanen)
            $draft->delete();
        }

        $this->info("✅ {$draftCount} draft expired berhasil dihapus.");
        $this->info("🗑️  {$fileCount} file temp terkait draft berhasil dihapus.");
    }
}
