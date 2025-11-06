<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Notifikasi;
use App\Models\Repositori;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $filter = request('filter');
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $items = collect();

        // -------------------
        // AMBIL ARTIKEL & REPOSITORI
        // -------------------
        if (Auth::check()) {
            $artikel = Artikel::with('user', 'tag')->latest()->get();
            $repositori = Repositori::with('user', 'fileRepo')->latest()->get();
        } else {
            $artikel = Artikel::where('status', 'publik')->with('user', 'tag')->latest()->get();
            $repositori = Repositori::where('status', 'publik')->with('user', 'fileRepo')->latest()->get();
        }

        // Artikel
        if (! $filter || $filter === 'artikel') {
            foreach ($artikel as $a) {
                $items->push([
                    'type' => 'artikel',
                    'id' => $a->id,
                    'judul' => $a->judul,
                    'isi' => $a->isi,
                    'created_at' => $a->created_at,
                    'user' => $a->user,
                    'views' => $a->viewArtikel()->count(),
                    'status' => $a->status,
                    'tag' => $a->tag,
                ]);
            }
        }

        // Repositori + file
        if (! $filter || $filter === 'repositori') {
            foreach ($repositori as $r) {
                $items->push([
                    'type' => 'repositori',
                    'id' => $r->id,
                    'judul' => $r->judul_repo,
                    'isi' => $r->deskripsi,
                    'created_at' => $r->created_at,
                    'user' => $r->user,
                    'status' => $r->status,
                    'tag' => null,
                ]);

                foreach ($r->fileRepo as $f) {
                    $ukuranMB = number_format($f->ukuran / (1024 * 1024), 2);
                    $badgeClass = match (strtolower($f->ekstensi)) {
                        'pdf' => 'bg-danger',
                        'doc', 'docx' => 'bg-primary',
                        'xls', 'xlsx' => 'bg-success',
                        'jpg', 'jpeg', 'png' => 'bg-warning',
                        'zip', 'rar', 'tar' => 'bg-info',
                        default => 'bg-secondary',
                    };
                    $items->push([
                        'type' => 'file',
                        'id' => $f->id,
                        'judul' => $f->nama_file,
                        'isi' => $ukuranMB.' MB | <span class="badge '.$badgeClass.'">'.strtoupper($f->ekstensi).'</span>',
                        'created_at' => $f->created_at,
                        'downloads' => $f->downloadLogs()->count(),
                        'user' => $f->user,
                        'repositori' => $r,
                        'tag' => null,
                    ]);
                }
            }
        }

        $items = $items->sortByDesc('created_at')->values();

        $pagedItems = new LengthAwarePaginator($items->forPage($currentPage, $perPage), $items->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        $recentActivities = collect();
        if (Auth::check()) {
            $recentActivities = Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $artikelRaw = DB::table('artikel')->selectRaw("MONTH(created_at) as bulan_num, DATE_FORMAT(created_at, '%b') as bulan, COUNT(*) as total")->whereYear('created_at', date('Y'))->groupBy('bulan_num', 'bulan')->orderBy('bulan_num')->pluck('total', 'bulan')->toArray();

        $repoRaw = DB::table('repositori')->selectRaw("MONTH(created_at) as bulan_num, DATE_FORMAT(created_at, '%b') as bulan, COUNT(*) as total")->whereYear('created_at', date('Y'))->groupBy('bulan_num', 'bulan')->orderBy('bulan_num')->pluck('total', 'bulan')->toArray();

        $downloadRaw = DB::table('download_logs')->selectRaw("MONTH(created_at) as bulan_num, DATE_FORMAT(created_at, '%b') as bulan, COUNT(*) as total")->whereYear('created_at', date('Y'))->groupBy('bulan_num', 'bulan')->orderBy('bulan_num')->pluck('total', 'bulan')->toArray();

        $visitorRaw = DB::table('visitors')->selectRaw("MONTH(`date`) as bulan_num, DATE_FORMAT(`date`, '%b') as bulan, COUNT(*) as total")->whereYear('date', date('Y'))->groupBy('bulan_num', 'bulan')->orderBy('bulan_num')->pluck('total', 'bulan')->toArray();

        $artikelStats = [];
        $repoStats = [];
        $visitorStats = [];
        $downloadStats = [];

        foreach ($months as $m) {
            $artikelStats[] = isset($artikelRaw[$m]) ? (int) $artikelRaw[$m] : 0;
            $repoStats[] = isset($repoRaw[$m]) ? (int) $repoRaw[$m] : 0;
            $downloadStats[] = $downloadRaw[$m] ?? 0;
            $visitorStats[] = isset($visitorRaw[$m]) ? (int) $visitorRaw[$m] : 0;
        }

        return view('index', [
            'items' => $pagedItems,
            'filter' => $filter,
            'months' => $months,
            'artikelStats' => $artikelStats,
            'repoStats' => $repoStats,
            'downloadStats' => $downloadStats,
            'visitorStats' => $visitorStats,
            'recentActivities' => $recentActivities,
        ]);
    }
}
