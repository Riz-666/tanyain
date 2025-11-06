<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminArtikelController extends Controller
{
    public function detail(string $id)
    {
        $artikel = Artikel::with('user', 'viewArtikel', 'repositori', 'tag')->findOrFail($id);

        return view('admin.artikel.detail', [
            'artikel' => $artikel,
        ]);
    }

    public function trashArtikel()
    {
        $artikel = Artikel::with('userTrash', 'repositoriSoftDelete')->onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        if (auth()->user()->role !== 'super_admin' && $artikel->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda bukan pemilik artikel ini.');
        }

        $trashedUsersCount = User::onlyTrashed()->count();
        $trashedArticlesCount = $artikel->count();
        $trashedReposCount = Repositori::onlyTrashed()->count();

        return view('admin.artikel.trash', [
            'type' => 'artikel',
            'data' => $artikel,
            'trashedUsersCount' => $trashedUsersCount,
            'trashedArticlesCount' => $trashedArticlesCount,
            'trashedReposCount' => $trashedReposCount,
        ]);
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if (auth()->user()->role !== 'super_admin' && $artikel->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda bukan pemilik artikel ini.');
        }

        // Pindah file utama
        if ($artikel->file && Storage::disk('public')->exists("artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/files", 0755, true);
            Storage::disk('public')->move("artikel/{$artikel->id}/files/{$artikel->file}", "trash/artikel/{$artikel->id}/files/{$artikel->file}");
        }

        // Pindah cover
        if ($artikel->cover && Storage::disk('public')->exists("artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/cover", 0755, true);
            Storage::disk('public')->move("artikel/{$artikel->id}/cover/{$artikel->cover}", "trash/artikel/{$artikel->id}/cover/{$artikel->cover}");
        }

        // Pindah gambar dari isi artikel
        if (!empty($artikel->isi)) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
            foreach ($matches[1] as $imgUrl) {
                $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    $trashPath = 'trash/' . $path;
                    Storage::disk('public')->makeDirectory(dirname($trashPath), 0755, true);
                    Storage::disk('public')->move($path, $trashPath);
                }
            }
        }

        // Soft delete
        $artikel->deleted_until = now()->addDays(20);
        $artikel->save();
        $artikel->delete();

        // ✅ HAPUS FOLDER ASLI SETELAH SEMUA FILE DIPINDAH
        $folderPath = 'artikel/' . $artikel->id;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        return redirect()->route('admin.aktivitas')->with('success', 'Artikel masuk trash (20 hari)');
    }

    public function restore($id)
{
    $artikel = Artikel::withTrashed()->findOrFail($id);

    if (auth()->user()->role !== 'super_admin' && $artikel->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Akses ditolak: Anda bukan pemilik artikel ini.');
    }

    // Pulihkan file utama
    if ($artikel->file && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/files/{$artikel->file}")) {
        Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/files", 0755, true);
        Storage::disk('public')->move(
            "trash/artikel/{$artikel->id}/files/{$artikel->file}",
            "artikel/{$artikel->id}/files/{$artikel->file}"
        );
    }

    // Pulihkan cover
    if ($artikel->cover && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/cover/{$artikel->cover}")) {
        Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/cover", 0755, true);
        Storage::disk('public')->move(
            "trash/artikel/{$artikel->id}/cover/{$artikel->cover}",
            "artikel/{$artikel->id}/cover/{$artikel->cover}"
        );
    }

    // Pulihkan gambar dari isi artikel
    if (!empty($artikel->isi)) {
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
        foreach ($matches[1] as $imgUrl) {
            $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
            $trashPath = 'trash/'.$path;
            if (Storage::disk('public')->exists($trashPath)) {
                Storage::disk('public')->makeDirectory(dirname($path), 0755, true);
                Storage::disk('public')->move($trashPath, $path);
            }
        }
    }

    // Pulihkan artikel
    $artikel->deleted_until = null;
    $artikel->restore();

    // ✅ CEK ISI FOLDER DULU SEBELUM HAPUS
    $trashFolder = 'trash/artikel/' . $artikel->id;
    if (Storage::disk('public')->exists($trashFolder)) {
        $files = Storage::disk('public')->allFiles($trashFolder);
        $dirs = Storage::disk('public')->allDirectories($trashFolder);

        // Hapus folder hanya jika benar-benar kosong
        if (empty($files) && empty($dirs)) {
            Storage::disk('public')->deleteDirectory($trashFolder);
        } else {
            Log::warning("Trash folder tidak dihapus karena masih ada file/folder: {$trashFolder}");
        }
    }

    return back()->with('success', 'Artikel berhasil direstore');
}

    public function forceDelete($id)
    {
        $artikel = Artikel::withTrashed()->findOrFail($id);

        if (auth()->user()->role !== 'super_admin' && $artikel->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda bukan pemilik artikel ini.');
        }

        // ✅ Hapus file utama dari trash
        if ($artikel->file && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->delete("trash/artikel/{$artikel->id}/files/{$artikel->file}");
        }

        // ✅ Hapus cover dari trash
        if ($artikel->cover && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->delete("trash/artikel/{$artikel->id}/cover/{$artikel->cover}");
        }

        // ✅ Hapus gambar dari isi artikel di trash
        if (!empty($artikel->isi)) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
            foreach ($matches[1] as $imgUrl) {
                $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                $trashPath = 'trash/' . $path;
                if (Storage::disk('public')->exists($trashPath)) {
                    Storage::disk('public')->delete($trashPath);
                }
            }
        }

        // ✅ Hapus folder trash artikel jika kosong
        $trashFolder = "trash/artikel/{$artikel->id}";
        if (Storage::disk('public')->exists($trashFolder)) {
            $files = Storage::disk('public')->allFiles($trashFolder);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($trashFolder);
            }
        }

        // Hapus artikel dari database
        $artikel->forceDelete();

        return back()->with('success', 'Artikel dihapus permanen');
    }
}
