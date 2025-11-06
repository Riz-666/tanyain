<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Komentar;
use App\Models\KomentarTag;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    public function store(Request $request, $artikelId)
    {
        $request->validate([
            'isi' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:komentar,id',
        ]);

        $komentar = Komentar::create([
            'artikel_id' => $artikelId,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'isi' => $request->isi,
        ]);

        // Kirim notifikasi ke pemilik artikel (jika bukan penulis komentar)
        $artikel = Artikel::findOrFail($artikelId);
        if ($artikel->user_id && $artikel->user_id != auth()->id()) {
            Notifikasi::create([
                'user_id' => $artikel->user_id,
                'tipe' => 'komentar',
                'referensi_id' => $komentar->id,
                'pesan' => auth()->user()->nama . ' berkomentar di artikel Anda.',
                'status' => 'belum_dibaca',
            ]);
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Komentar $komentar)
    {
        if ($komentar->user_id !== auth()->id()) {
            abort(403, 'Anda tidak punya akses untuk menghapus komentar ini.');
        }
        $komentar->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'isi' => 'required|string|max:2000',
            'tagged_user_id' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|exists:komentar,id',
        ]);

        $parent = Komentar::findOrFail($id);

        $komentar = Komentar::create([
            'artikel_id' => $parent->artikel_id,
            'user_id' => auth()->id(),
            'parent_id' => $parent->id,
            'isi' => $request->isi,
        ]);

        // Tag user jika ada
        $taggedUserId = $request->input('tagged_user_id');
        if ($taggedUserId && $taggedUserId != auth()->id()) {
            KomentarTag::firstOrCreate([
                'komentar_id' => $komentar->id,
                'tagged_user_id' => $taggedUserId,
            ]);

            Notifikasi::create([
                'user_id' => $taggedUserId,
                'tipe' => 'tag',
                'referensi_id' => $komentar->id,
                'pesan' => auth()->user()->nama . ' Menyebut Anda pada sebuah komentar.',
                'status' => 'belum_dibaca',
            ]);
        }

        // Notifikasi ke pemilik artikel (jika bukan yang reply)
        $artikelOwnerId = $parent->artikel->user_id ?? null;
        if ($artikelOwnerId && $artikelOwnerId != auth()->id()) {
            Notifikasi::create([
                'user_id' => $artikelOwnerId,
                'tipe' => 'balasan',
                'referensi_id' => $komentar->id,
                'pesan' => auth()->user()->nama . ' membalas komentar pada artikel Anda.',
                'status' => 'belum_dibaca',
            ]);
        }
 
        return back();
    }
}
