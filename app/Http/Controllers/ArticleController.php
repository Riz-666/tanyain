<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\viewArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function article()
    {
        if (Auth::check()) {
            $artikel = Artikel::latest()->paginate(5);
        } else {
            $artikel = Artikel::with('user', 'repositori', 'viewArtikel')->where('status', 'publik')->latest()->latest()->paginate(5);
        }
        $totalArtikel = Artikel::count();
        return view('article', [
            'artikel' => $artikel,
            'totalArtikel' => $totalArtikel,
        ]);
    }

    public function article_detail(Request $request, string $id)
    {
        $artikel = Artikel::with('user', 'viewArtikel', 'repositori')->findOrFail($id);

        // Coba buat views/ip
        $userId = Auth()->id();
        $ip = $request->ip();

        $sudahDilihat = viewArtikel::where('artikel_id', $artikel->id)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('ip_address', $ip))
            ->exists();

        if (!$sudahDilihat) {
            viewArtikel::create([
                'artikel_id' => $artikel->id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'created_at' => now(),
            ]);
        }

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
        $validatedData = $request->validate(
            [
                'repositori_id' => 'exists:repositori,id',
                'judul' => 'required',
                'isi' => 'required',
                'file' => 'mimes:pdf|max:1048576',
            ],
            [
                'file.files' => 'Format Dokumen Tidak Di Dukung.',
                'file.max' => 'Ukuran File Terlalu Besar.',
            ],
        );

        $repo = Repositori::find($validatedData['repositori_id']);
        $status = $repo ? $repo->status : 'publik';

        $filename = null;
        if ($request->hasFile('file')) {
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('public/artikel-file', $filename);
        }

        $artikel = Artikel::create([
            'user_id' => auth()->id(),
            'repositori_id' => $validatedData['repositori_id'],
            'judul' => $validatedData['judul'],
            'slug' => Str::slug($validatedData['judul']),
            'isi' => $validatedData['isi'],
            'file' => $filename,
            'status' => $status,
            'views' => 0,
        ]);

        return redirect()->route('article')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->user_id !== auth()->id()) {
            return redirect()->route('index')->with('auth', 'Akses ditolak: kamu bukan pemilik artikel ini.');
        }

        $repositori = Repositori::all();
        return view('edit_article', [
            'artikel' => $artikel,
            'repositori' => $repositori,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:1048576',
            'repositori_id' => 'nullable|exists:repositori,id',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->judul = $request->judul;
        $artikel->isi = $request->isi;
        $artikel->repositori_id = $request->repositori_id;

        if ($request->hasFile('file')) {
            if ($artikel->file && file_exists(public_path('storage/artikel-file/' . $artikel->file))) {
                unlink(public_path('storage/artikel-file/' . $artikel->file));
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/artikel-file'), $filename);

            $artikel->file = $filename;
        }
        $artikel->save();

        return redirect()->route('article.detail', $artikel->id)->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $artikel = Artikel::findOrFail($id);

        $path = 'artikel-file/' . $artikel->file;

        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }

        $artikel->delete();

        return redirect()
            ->route('profile', Auth::user()->id)
            ->with('success', 'Artikel berhasil dihapus.');
    }

    // PERCOBAAN
}
