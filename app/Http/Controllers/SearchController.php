<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search', '');
        $tab = $request->input('tab', 'all');
        $tagFilter = $request->input('tag', '');
        $isLogin = auth()->check();
        $perPage = 5;

        // Jika ada filter tag, khusus artikel
        if ($tagFilter) {
            $artikels = Artikel::query()
                ->when(! $isLogin, fn ($q) => $q->where('status', 'publik'))
                ->whereHas('tag', fn ($q) => $q->where('nama_tag', $tagFilter))
                ->with('user', 'tag')
                ->paginate($perPage, ['*'], 'page');

            // Tambah parameter tab ke pagination
            $artikels->appends(['search' => $keyword, 'tab' => $tab, 'tag' => $tagFilter]);

            // Inisialisasi repos sebagai collection kosong
            $repos = collect();

            return view('user.search', compact('artikels', 'repos', 'keyword', 'tab', 'tagFilter'));
        }

        // Inisialisasi dengan collection kosong
        $artikels = collect();
        $repos = collect();

        // Query hanya berdasarkan tab yang aktif
        if ($tab === 'artikel') {
            $artikels = Artikel::query()
                ->when(! $isLogin, fn ($q) => $q->where('status', 'publik'))
                ->where(function ($query) use ($keyword) {
                    $query->where('judul', 'like', "%{$keyword}%")
                        ->orWhere('isi', 'like', "%{$keyword}%")
                        ->orWhereHas('tag', fn ($q) => $q->where('nama_tag', 'like', "%{$keyword}%"));
                })
                ->with('user', 'tag')
                ->paginate($perPage, ['*'], 'page');

        } elseif ($tab === 'repositori') {
            $repos = Repositori::query()
                ->when(! $isLogin, fn ($q) => $q->where('status', 'publik'))
                ->where(function ($query) use ($keyword) {
                    $query->where('judul_repo', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%");
                })
                ->with('user', 'fileRepo.downloadLogs')
                ->withCount('fileRepo as jumlah_file')
                ->paginate($perPage, ['*'], 'page');

            // Hitung total download per repo
            $repos->getCollection()->transform(function ($repo) {
                $repo->download_count = $repo->fileRepo->sum(fn ($file) => $file->downloadLogs->count());
                return $repo;
            });

        } else {
            // tab === 'all'
            $artikels = Artikel::query()
                ->when(! $isLogin, fn ($q) => $q->where('status', 'publik'))
                ->where(function ($query) use ($keyword) {
                    $query->where('judul', 'like', "%{$keyword}%")
                        ->orWhere('isi', 'like', "%{$keyword}%")
                        ->orWhereHas('tag', fn ($q) => $q->where('nama_tag', 'like', "%{$keyword}%"));
                })
                ->with('user', 'tag')
                ->paginate($perPage, ['*'], 'page_artikel');

            $repos = Repositori::query()
                ->when(! $isLogin, fn ($q) => $q->where('status', 'publik'))
                ->where(function ($query) use ($keyword) {
                    $query->where('judul_repo', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%");
                })
                ->with('user', 'fileRepo.downloadLogs')
                ->withCount('fileRepo as jumlah_file')
                ->paginate($perPage, ['*'], 'page_repos');

            // Hitung download count untuk tab "all"
            $repos->getCollection()->transform(function ($repo) {
                $repo->download_count = $repo->fileRepo->sum(fn ($file) => $file->downloadLogs->count());
                return $repo;
            });
        }

        // Data untuk sidebar (bisa tetap diambil)
        $totalArtikel = $isLogin
            ? Artikel::count()
            : Artikel::where('status', 'publik')->count();

        $totalUser = User::where('role', 'user')->count();
        $allTags = Tag::all();

        return view('user.search', compact(
            'artikels', 'repos', 'keyword', 'tab', 'tagFilter',
            'totalArtikel', 'totalUser', 'allTags'
        ));
    }
}
