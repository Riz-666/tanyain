<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visitor;
use App\Models\VisitorSummary;
use App\Models\Artikel;
use App\Models\ArtikelSummary;
use App\Models\Repositori;
use App\Models\RepoSummary;
use Carbon\Carbon;

class UpdateMonthlySummary extends Command
{
    protected $signature = 'summary:update-monthly';
    protected $description = 'Update monthly summary for visitor, artikel, repo';

    public function handle()
    {
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        // Visitor summary
        $totalVisitor = Visitor::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->distinct('ip_address')
            ->count('ip_address');
        VisitorSummary::updateOrCreate(
            ['year'=>$year,'month'=>$month],
            ['total_visitors'=>$totalVisitor]
        );

        // Artikel summary
        $totalArtikel = Artikel::whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->count();
        ArtikelSummary::updateOrCreate(
            ['year'=>$year,'month'=>$month],
            ['total_artikel'=>$totalArtikel]
        );

        // Repo summary
        $totalRepo = Repositori::whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->count();
        RepoSummary::updateOrCreate(
            ['year'=>$year,'month'=>$month],
            ['total_repo'=>$totalRepo]
        );

        // Hapus visitor >30 hari
        Visitor::where('date', '<', Carbon::now()->subDays(31))->delete();

        $this->info('Monthly summary updated!');
    }
}
