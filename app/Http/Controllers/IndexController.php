<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $filter = request('filter');
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if (Auth::check()) {
            $artikel = Artikel::with('user')->latest()->get();
            $repositori = Repositori::with('user')->latest()->get();
        } else {
            $artikel = Artikel::where('status', 'publik')->with('user')->latest()->get();
            $repositori = Repositori::where('status', 'publik')->with('user')->latest()->get();
        }

        $items = collect();

        if (!$filter || $filter === 'artikel') {
            foreach ($artikel as $a) {
                $items->push([
                    'type' => 'artikel',
                    'id' => $a->id,
                    'judul' => $a->judul,
                    'isi' => $a->isi,
                    'created_at' => $a->created_at,
                    'user' => $a->user,
                    'status' => $a->status,
                ]);
            }
        }

        if (!$filter || $filter === 'repositori') {
            foreach ($repositori as $r) {
                $items->push([
                    'type' => 'repositori',
                    'id' => $r->id,
                    'judul' => $r->judul_repo,
                    'isi' => $r->deskripsi,
                    'created_at' => $r->created_at, 
                    'user' => $r->user,
                    'status' => $r->status,
                ]);
            }
        }

        $items = $items->sortByDesc('created_at')->values();

        $pagedItems = new LengthAwarePaginator
                    ($items->forPage($currentPage, $perPage),
                    $items->count(),
                        $perPage,
                        $currentPage,
                        [
                            'path' => request()->url(),
                            'query' => request()->query()
                        ]);

        return view('index', [
            'items' => $pagedItems,
            'filter' => $filter,
        ]);
    }
}
