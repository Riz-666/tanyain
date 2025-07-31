<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function article()
    {
        $artikel = Artikel::with('user','repositori','viewArtikel')->get();
        $totalArtikel = Artikel::count();
        return view('article',[
            'artikel' => $artikel,
            'totalArtikel' => $totalArtikel
        ]);
    }

    public function article_detail(String $id)
    {
        $artikel = Artikel::with('user','repositori','viewArtikel')->findOrFail($id);
        $repo = Repositori::with('artikel')->first();
        return view('article_detail',[
            'artikel' => $artikel,
            'repo' => $repo
        ]);
    }

    public function create_article()
    {
        return view('create_article');
    }
    public function add_artikel(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'file' => 'mimes:pdf,doc,docx,zip,xlxs|max:1048576',
            'status' => 'required|in:publik,private',
        ]);

        $artikel = Artikel::create([
            'user_id' => auth()->id(),
            'judul' => $validatedData['judul'],
            'slug' => Str::slug($validatedData['judul']),
            'isi' => $validatedData['isi'],
            'status' => $validatedData['status'],
            'views' => 0,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama_file = $file->getClientOriginalName();
            $tipeFile = $file->getClientOriginalExtension();

            $path = $file->storeAs('public/repositori', $nama_file);

            Repositori::create([
                'artikel_id' => $artikel->id,
                'nama_file' => $nama_file,
                'tipe_file' => $tipeFile,
            ]);
        }

        return redirect()->route('article')->with('success', 'Artikel berhasil ditambahkan');
    }
}
