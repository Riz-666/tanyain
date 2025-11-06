<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    public function trash()
    {
        $userId = Auth::id();

        // Ambil artikel & repositori milik user yg login
        $artikels = Artikel::onlyTrashed()->where('user_id', $userId)->get();
        $repos = Repositori::onlyTrashed()->where('user_id', $userId)->get();

        $trashItems = collect();

        foreach ($artikels as $artikel) {
            $artikel->tipe = 'Artikel';
            $trashItems->push($artikel);
        }

        foreach ($repos as $repo) {
            $repo->tipe = 'Repositori';
            $repo->judul = $repo->judul_repo;
            $trashItems->push($repo);
        }

        // ================================================
        // ✅ HITUNG STATISTIK DINAMIS (khusus user login)
        // ================================================
        $totalSampah = $trashItems->count();

        $willExpireCount = $trashItems
            ->filter(function ($item) {
                $restoreDeadline = Carbon::parse($item->deleted_at)->addDays(30);
                $daysLeft = $restoreDeadline->diffInDays(Carbon::now());

                return $daysLeft <= 3 && $daysLeft >= 0;
            })
            ->count();

        $todayStart = Carbon::today();
        $deletedTodayCount = $trashItems
            ->filter(function ($item) use ($todayStart) {
                return Carbon::parse($item->deleted_at)->gte($todayStart);
            })
            ->count();

        return view('user.trash', compact('trashItems', 'totalSampah', 'willExpireCount', 'deletedTodayCount'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih.'], 400);
        }

        $deletedCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            try {
                $deleted = false;

                // ✅ CEK ARTIKEL
                $item = Artikel::withTrashed()->find($id);
                if ($item && $item->trashed()) {
                    // ✅ Hapus file PDF/artikel dengan struktur baru
                    if ($item->file && Storage::disk('public')->exists("trash/artikel/{$item->id}/files/{$item->file}")) {
                        Storage::disk('public')->delete("trash/artikel/{$item->id}/files/{$item->file}");
                    }

                    // ✅ Hapus cover dengan struktur baru
                    if ($item->cover && Storage::disk('public')->exists("trash/artikel/{$item->id}/cover/{$item->cover}")) {
                        Storage::disk('public')->delete("trash/artikel/{$item->id}/cover/{$item->cover}");
                    }

                    // ✅ Hapus gambar dari isi artikel (dinamis path)
                    if (! empty($item->isi)) {
                        preg_match_all('/<img[^>]+src="([^">]+)"/i', $item->isi, $matches);
                        foreach ($matches[1] as $imgUrl) {
                            $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                            $trashPath = 'trash/'.$path;

                            if (Storage::disk('public')->exists($trashPath)) {
                                Storage::disk('public')->delete($trashPath);
                            }
                        }
                    }

                    // ✅ Hapus folder artikel di trash jika kosong
                    $artikelTrashPath = "trash/artikel/{$item->id}";
                    if (Storage::disk('public')->exists($artikelTrashPath)) {
                        $files = Storage::disk('public')->allFiles($artikelTrashPath);
                        if (empty($files)) {
                            Storage::disk('public')->deleteDirectory($artikelTrashPath);
                        }
                    }

                    $item->forceDelete();
                    $deletedCount++;
                    $deleted = true;
                }

                // ✅ CEK REPOSITORI — TETAP DIEKSEKUSI MESKI ARTIKEL SUDAH DIPROSES
                $repo = Repositori::withTrashed()->with('fileRepo', 'artikel')->find($id);
                if ($repo && $repo->trashed()) {
                    // Reset relasi artikel
                    foreach ($repo->artikel as $artikel) {
                        $artikel->update(['repositori_id' => null]);
                    }

                    // ✅ Hapus file tambahan — gunakan prefix repo_id_
                    foreach ($repo->fileRepo as $file) {
                        $trashFileName = $repo->id.'_'.$file->nama_file; // ✅ INI KUNCI PERUBAHAN!
                        $trashPath = 'repositori/trash/'.$trashFileName;

                        if (Storage::disk('public')->exists($trashPath)) {
                            Storage::disk('public')->delete($trashPath);
                        }
                    }

                    // Hapus relasi file
                    $repo->fileRepo()->delete();

                    // Hapus repositori
                    $repo->forceDelete();
                    $deletedCount++;
                    $deleted = true;
                }

                // Jika tidak ada yang dihapus
                if (! $deleted) {
                    $errors[] = "Item ID {$id} tidak ditemukan di sampah (mungkin sudah dihapus permanen atau tidak ada).";
                }
            } catch (\Exception $e) {
                $errors[] = "Gagal hapus item ID {$id}: ".$e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$deletedCount item berhasil dihapus permanen.",
            'errors' => $errors,
        ]);
    }

    public function bulkRestore(Request $request)
{
    $ids = $request->input('ids', []);
    if (empty($ids)) {
        return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih.'], 400);
    }

    \Log::info('Bulk Restore Diakses', [
        'ids' => $ids,
        'timestamp' => now(),
        'user_id' => Auth::id(),
    ]);

    $restoredCount = 0;
    $errors = [];

    foreach ($ids as $id) {
        try {
            $restored = false;

            // ✅ CEK ARTIKEL (hanya milik user login)
            $item = Artikel::withTrashed()->find($id);
            if ($item && $item->trashed()) {
                if ($item->user_id !== Auth::id()) {
                    $errors[] = "Kamu tidak punya akses untuk artikel ID {$id}.";
                } else {
                    // ✅ Restore file PDF/artikel
                    if ($item->file && Storage::disk('public')->exists("trash/artikel/{$item->id}/files/{$item->file}")) {
                        Storage::disk('public')->makeDirectory("artikel/{$item->id}/files", 0755, true);
                        Storage::disk('public')->move(
                            "trash/artikel/{$item->id}/files/{$item->file}",
                            "artikel/{$item->id}/files/{$item->file}"
                        );
                    }

                    // ✅ Restore cover
                    if ($item->cover && Storage::disk('public')->exists("trash/artikel/{$item->id}/cover/{$item->cover}")) {
                        Storage::disk('public')->makeDirectory("artikel/{$item->id}/cover", 0755, true);
                        Storage::disk('public')->move(
                            "trash/artikel/{$item->id}/cover/{$item->cover}",
                            "artikel/{$item->id}/cover/{$item->cover}"
                        );
                    }

                    // ✅ Restore gambar isi artikel
                    if (!empty($item->isi)) {
                        preg_match_all('/<img[^>]+src="([^">]+)"/i', $item->isi, $matches);
                        foreach ($matches[1] as $imgUrl) {
                            $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                            $trashPath = 'trash/' . $path;

                            if (Storage::disk('public')->exists($trashPath)) {
                                Storage::disk('public')->makeDirectory(dirname($path), 0755, true);
                                Storage::disk('public')->move($trashPath, $path);
                            }
                        }
                    }

                    $item->deleted_until = null;
                    $item->restore();
                    $restoredCount++;
                    $restored = true;

                    // ✅ HAPUS FOLDER TRASH ARTIKEL SETELAH RESTORE
                    $trashFolder = "trash/artikel/{$item->id}";
                    if (Storage::disk('public')->exists($trashFolder)) {
                        Storage::disk('public')->deleteDirectory($trashFolder);
                    }
                }
            }

            // ✅ CEK REPOSITORI (hanya milik user login)
            $repo = Repositori::withTrashed()->with('fileRepo')->find($id);
            if ($repo && $repo->trashed()) {
                if ($repo->user_id !== Auth::id()) {
                    $errors[] = "Kamu tidak punya akses untuk repositori ID {$id}.";
                } else {
                    $repo->restore();
                    $repo->deleted_until = null;
                    $repo->save();

                    foreach ($repo->fileRepo as $file) {
                        $trashFileName = $repo->id . '_' . $file->nama_file;
                        $trashPath = 'repositori/trash/' . $trashFileName;
                        $originalPath = 'repositori/' . $repo->id . '/' . $file->nama_file;

                        if (Storage::disk('public')->exists($trashPath)) {
                            Storage::disk('public')->makeDirectory('repositori/' . $repo->id, 0755, true);
                            Storage::disk('public')->move($trashPath, $originalPath);
                        }
                    }

                    $restoredCount++;
                    $restored = true;
                }
            }

            if (!$restored) {
                $errors[] = "Item ID {$id} tidak ditemukan di sampah / sudah dipulihkan.";
            }
        } catch (\Exception $e) {
            $errors[] = "Gagal pulihkan item ID {$id}: " . $e->getMessage();
        }
    }

    return response()->json([
        'success' => true,
        'message' => "$restoredCount item berhasil dipulihkan.",
        'errors' => $errors,
    ]);
}
}
