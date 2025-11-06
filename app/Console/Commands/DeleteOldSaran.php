<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Saran;
use Carbon\Carbon;

class DeleteOldSaran extends Command
{
    protected $signature = 'saran:delete-old';
    protected $description = 'Hapus saran yang lebih dari 10 hari';

    public function handle()
    {
        $count = Saran::where('created_at', '<', Carbon::now()->subDays(10))->delete();
        $this->info("Berhasil menghapus {$count} saran lama.");
    }
}
