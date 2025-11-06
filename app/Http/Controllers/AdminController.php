<?php

namespace App\Http\Controllers;

use App\Models\ArtikelSummary;
use App\Models\RepoSummary;
use App\Models\VisitorSummary;
use App\Models\Artikel;
use App\Models\FileRepo;
use App\Models\Repositori;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
Carbon::setLocale('id');
setlocale(LC_TIME, 'id_ID.UTF-8');

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Bulan 1-12 dengan nama yang lebih lengkap
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->format('M'));

        // 2. Data untuk setiap bulan
        $dataVisitor = $months->map(function ($m, $i) {
            $s = VisitorSummary::where('month', $i + 1)->first();
            return $s->total_visitors ?? 0;
        });

        $dataArtikel = $months->map(function ($m, $i) {
            $s = ArtikelSummary::where('month', $i + 1)->first();
            return $s->total_artikel ?? 0;
        });

        $dataRepo = $months->map(function ($m, $i) {
            $s = RepoSummary::where('month', $i + 1)->first();
            return $s->total_repo ?? 0;
        });

        // 3. Recap bulan sekarang & sebelumnya - ALWAYS SHOW
        $currentMonthIndex = now()->month - 1;
        $prevMonthIndex = $currentMonthIndex - 1 >= 0 ? $currentMonthIndex - 1 : 11;

        $recap = [
            'current' => [
                'month' => $months[$currentMonthIndex],
                'visitor' => $dataVisitor[$currentMonthIndex],
                'artikel' => $dataArtikel[$currentMonthIndex],
                'repo' => $dataRepo[$currentMonthIndex],
            ],
            'previous' => [
                'month' => $months[$prevMonthIndex],
                'visitor' => $dataVisitor[$prevMonthIndex],
                'artikel' => $dataArtikel[$prevMonthIndex],
                'repo' => $dataRepo[$prevMonthIndex],
            ],
            // Summary totals untuk stats cards
            'total_visitor' => $dataVisitor->sum(),
            'total_artikel' => $dataArtikel->sum(),
            'total_repo' => $dataRepo->sum(),
            // Growth percentage (current vs previous)
            'visitor_growth' => $this->calculateGrowth($dataVisitor[$prevMonthIndex], $dataVisitor[$currentMonthIndex]),
            'artikel_growth' => $this->calculateGrowth($dataArtikel[$prevMonthIndex], $dataArtikel[$currentMonthIndex]),
            'repo_growth' => $this->calculateGrowth($dataRepo[$prevMonthIndex], $dataRepo[$currentMonthIndex]),
        ];

        // 4. Activities data
        $artikel = Artikel::select('id', 'user_id', 'judul as activity', 'created_at', DB::raw("'artikel' as type"))->with('user:id,nama')->get();

        $repo = Repositori::select('id', 'user_id', 'judul_repo as activity', 'created_at', DB::raw("'repo' as type"))->with('user:id,nama')->get();

        $file = FileRepo::select('id', DB::raw('NULL as user_id'), 'nama_file as activity', 'created_at', DB::raw("'file' as type"))->get();

        $allActivities = $artikel
            ->concat($repo)
            ->concat($file)
            ->map(function ($item) {
                $item->created_at = $item->created_at ? Carbon::parse($item->created_at) : Carbon::now();
                return $item;
            })
            ->sortByDesc(fn($item) => $item->created_at->timestamp)
            ->values();

        $user = User::get();

        return view('admin.dashboard', [
            'user' => $user,
            'allActivities' => $allActivities,
            'dataVisitor' => $dataVisitor,
            'dataArtikel' => $dataArtikel,
            'dataRepo' => $dataRepo,
            'months' => $months,
            'recap' => $recap,
        ]);
    }

    /**
     * Calculate growth percentage between two values
     */
    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
