<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function article()
    {
        if(Auth::check()){
            $artikel = Artikel::latest()->paginate(5);
        }else{
            $artikel = Artikel::with('user','repositori', 'viewArtikel')->where('status', 'publik')->latest()->latest()->paginate(5);
        }
        $totalArtikel = Artikel::count();
        return view('article', [
            'artikel' => $artikel,
            'totalArtikel' => $totalArtikel,
        ]);
    }

    public function article_detail(string $id)
    {
        $artikel = Artikel::with('user', 'viewArtikel', 'repositori')->findOrFail($id);
        $repo = Repositori::with('artikel')->first();
        return view('article_detail', [
            'artikel' => $artikel,
        ]);
    }

    public function create_article()
    {
        $repositori = Repositori::all();
        return view('create_article', [
            'repositori' => $repositori,
        ]);
    }
    public function add_artikel(Request $request)
    {
        $validatedData = $request->validate([
            'repositori_id' => 'exists:repositori,id',
            'judul' => 'required',
            'isi' => 'required',
            'file' => 'mimes:pdf,doc,docx,zip,xlxs,jpg,jpeg,png,gif|max:1048576',
        ]);

        $repo = Repositori::find($validatedData['repositori_id']);
        $status = $repo ? $repo->status : 'publik';

        $artikel = Artikel::create([
            'user_id' => auth()->id(),
            'repositori_id' => $validatedData['repositori_id'],
            'judul' => $validatedData['judul'],
            'slug' => Str::slug($validatedData['judul']),
            'isi' => $validatedData['isi'],
            'status' => $status,
            'views' => 0,
        ]);

        return redirect()->route('article')->with('success', 'Artikel berhasil ditambahkan');
    }
}
