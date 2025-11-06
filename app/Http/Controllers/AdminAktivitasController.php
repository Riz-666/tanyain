<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminAktivitasController extends Controller
{


    public function aktivitas(Request $request)
{
    $filter = $request->get('filter', 'all');
    $search = trim($request->get('search', ''));
    $perPage = 5;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    // helper untuk format author
    $formatAuthor = function ($user) {
        $nama = $user?->nama;
        $avatar = $user?->foto;
        return [
            'name' => $nama ?? 'Pengguna Di Non-Aktifkan',
            'avatar' => $avatar,
            'initials' => $nama ? strtoupper(substr($nama, 0, 2)) : '<i class="fa fa-user"></i>',
        ];
    };

    if ($filter === 'artikel') {
        $query = Artikel::with(['user' => fn($q) => $q->withTrashed(), 'tag'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }

        $paginated = $query->paginate($perPage)->withQueryString();
        $activities = $paginated->getCollection()->map(function ($item) use ($formatAuthor) {
            return [
                'id' => $item->id,
                'type' => 'artikel',
                'judul' => $item->judul ?? '(tidak ada judul)',
                'isi' => $item->isi,
                'status' => $item->status,
                'views' => $item->views,
                'tag' => $item->tag->take(3),
                'created_at' => $item->created_at,
                'author' => $formatAuthor($item->user),
            ];
        });

        $activities = new LengthAwarePaginator(
            $activities,
            $paginated->total(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

    } elseif ($filter === 'repositori') {
        $query = Repositori::with(['user' => fn($q) => $q->withTrashed()])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where('judul_repo', 'like', "%{$search}%");
        }

        $paginated = $query->paginate($perPage)->withQueryString();
        $activities = $paginated->getCollection()->map(function ($item) use ($formatAuthor) {
            return [
                'id' => $item->id,
                'type' => 'repositori',
                'judul' => $item->judul_repo,
                'deskripsi' => $item->deskripsi ?? '',
                'status' => $item->status,
                'created_at' => $item->created_at,
                'author' => $formatAuthor($item->user),
            ];
        });

        $activities = new LengthAwarePaginator(
            $activities,
            $paginated->total(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

    } else { // all
        $artikels = Artikel::with(['user' => fn($q) => $q->withTrashed(), 'tag'])
            ->orderByDesc('created_at');

        $repos = Repositori::with(['user' => fn($q) => $q->withTrashed()])
            ->orderByDesc('created_at');

        // Terapkan pencarian
        if ($search) {
            $artikels->where('judul', 'like', "%{$search}%");
            $repos->where('judul_repo', 'like', "%{$search}%");
        }

        $artikels = $artikels->get();
        $repos = $repos->get();

        // merge collection model asli dulu
        $mergedModels = $artikels->merge($repos)->sortByDesc('created_at')->values();

        // slice pagination
        $paginatedModels = $mergedModels->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // mapping ke array
        $activities = $paginatedModels->map(function ($item) use ($formatAuthor) {
            if ($item instanceof Artikel) {
                return [
                    'id' => $item->id,
                    'type' => 'artikel',
                    'judul' => $item->judul ?? '(tidak ada judul)',
                    'isi' => $item->isi,
                    'status' => $item->status,
                    'views' => $item->views,
                    'tag' => $item->tag->take(3),
                    'created_at' => $item->created_at,
                    'author' => $formatAuthor($item->user),
                ];
            } else { // Repositori
                return [
                    'id' => $item->id,
                    'type' => 'repositori',
                    'judul' => $item->judul_repo ?? '',
                    'deskripsi' => $item->deskripsi ?? '',
                    'status' => $item->status ?? '',
                    'created_at' => $item->created_at ?? '',
                    'author' => $formatAuthor($item->user),
                ];
            }
        });

        $activities = new LengthAwarePaginator(
            $activities,
            $mergedModels->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    return view('admin.terbaru', compact('activities', 'filter'));
}
}
