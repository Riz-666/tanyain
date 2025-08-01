<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $isLogin = auth()->check();

        // Query Repositori
        $repos = Repositori::query()
            ->when(!$isLogin, fn($q) => $q->where('status', 'publik'))
            ->where(function ($query) use ($keyword) {
                $query->where('judul_repo', 'like', "%{$keyword}%")
                      ->orWhere('deskripsi', 'like', "%{$keyword}%");
            })
            ->with('user')
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'Repositori',
                    'judul' => $item->judul_repo,
                    'deskripsi' => Str::limit(strip_tags($item->deskripsi), 100),
                    'link' => route('repo.detail', $item->id),
                    'tanggal' => $item->created_at,
                    'user' => $item->user->nama,
                ];
            });

        // Query Artikel
        $artikels = Artikel::query()
            ->when(!$isLogin, fn($q) => $q->where('status', 'publik'))
            ->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%")
                      ->orWhere('isi', 'like', "%{$keyword}%");
            })
            ->with('user')
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'Artikel',
                    'judul' => $item->judul,
                    'deskripsi' => Str::limit(strip_tags($item->isi), 100),
                    'link' => route('article.detail', $item->id),
                    'tanggal' => $item->created_at,
                    'user' => $item->user->nama,
                ];
            });

        // Gabungkan & urutkan berdasarkan tanggal terbaru
        $results = $repos->merge($artikels)->sortByDesc('tanggal');

        return view('search', compact('results', 'keyword'));
    }
}
