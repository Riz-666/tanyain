<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\FileRepo;
use App\Models\Repositori;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminRepoController extends Controller
{
    public function detail(string $id)
    {
        $repo = Repositori::with('fileRepo', 'artikel')->findOrFail($id);

        return view('admin.repositori.detail', [
            'repo' => $repo,
        ]);
    }

    public function trashRepo()
    {
        $repo = Repositori::with('userTrash', 'artikelTrash')->onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        $trashedUsersCount = User::onlyTrashed()->count();
        $trashedArticlesCount = Artikel::onlyTrashed()->count();
        $trashedReposCount = $repo->count();

        return view('admin.repositori.trash', [
            'type' => 'repo',
            'data' => $repo,
            'trashedUsersCount' => $trashedUsersCount,
            'trashedArticlesCount' => $trashedArticlesCount,
            'trashedReposCount' => $trashedReposCount,
        ]);
    }

public function destroy($id)
{
    $repo = Repositori::with('fileRepo', 'artikel')->findOrFail($id);

    if (auth()->user()->role !== 'super_admin' && $repo->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Anda Tidak Punya Akses');
    }

    foreach ($repo->artikel as $artikel) {
        $artikel->update(['repositori_id' => null]);
    }

    foreach ($repo->fileRepo as $file) {
        $oldPath = 'repositori/'.$repo->id.'/'.$file->nama_file;
        $trashFileName = $repo->id.'_'.$file->nama_file;
        $trashPath = 'repositori/trash/'.$trashFileName;

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->move($oldPath, $trashPath);
        }
    }

    // ✅ HAPUS FOLDER ASLI SETELAH SEMUA FILE DIPINDAH
    $folderPath = 'repositori/' . $repo->id;
    if (Storage::disk('public')->exists($folderPath)) {
        Storage::disk('public')->deleteDirectory($folderPath);
    }

    $repo->deleted_until = now()->addDays(20);
    $repo->save();
    $repo->delete();

    return redirect()->route('admin.aktivitas')->with('success', 'Repositori berhasil dihapus sementara. Bisa di-restore dalam 20 hari.');
}

public function restore($id)
{
    $repo = Repositori::withTrashed()->findOrFail($id);

    if (auth()->user()->role !== 'super_admin' && $repo->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Anda Tidak Punya Akses');
    }

    $repo->restore();
    $repo->deleted_until = null;
    $repo->save();

    foreach ($repo->fileRepo()->withTrashed()->get() as $file) {
        $trashFileName = $repo->id.'_'.$file->nama_file;
        $trashPath = 'repositori/trash/'.$trashFileName;
        $originalPath = 'repositori/'.$repo->id.'/'.$file->nama_file;

        // ✅ BUAT FOLDER BARU SEBELUM PINDAH FILE
        if (!Storage::disk('public')->exists('repositori/' . $repo->id)) {
            Storage::disk('public')->makeDirectory('repositori/' . $repo->id, 0755, true);
        }

        if (Storage::disk('public')->exists($trashPath)) {
            Storage::disk('public')->move($trashPath, $originalPath);
        }

        $file->restore();
    }

    return redirect()->back()->with('success', 'Repositori berhasil dikembalikan beserta semua file.');
}

    public function forceDelete($id)
    {
        $repo = Repositori::withTrashed()->with('fileRepo', 'artikel')->findOrFail($id);

        if (auth()->user()->role !== 'super_admin' && $repo->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda Tidak Punya Akses');
        }

        foreach ($repo->artikel as $artikel) {
            $artikel->update(['repositori_id' => null]);
        }

        foreach ($repo->fileRepo as $file) {
            $trashFileName = $repo->id.'_'.$file->nama_file; // ✅ Gunakan prefix
            $trashPath = 'repositori/trash/'.$trashFileName;

            if (Storage::disk('public')->exists($trashPath)) {
                Storage::disk('public')->delete($trashPath);
            }
        }

        $repo->fileRepo()->forceDelete();
        $repo->forceDelete();

        return back()->with('success', 'Repositori berhasil dihapus permanen.');
    }

    public function showPdf($id)
    {
        $file = FileRepo::findOrFail($id);

        // Normalisasi path
        $path = str_replace('\\', '/', $file->path);
        $path = preg_replace('#/+#', '/', $path);
        $path = str_replace('public/', '', $path);

        if (! str_contains($path, 'tambahan_file/')) {
            $path = str_replace('tambahan_file', 'tambahan_file/', $path);
        }

        $fullPath = public_path('storage/'.$path);

        if (! file_exists($fullPath)) {
            return redirect()->back()->with('error', 'Tidak ada file untuk ditinjau.');
        }

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$file->nama_file.'"',
        ]);
    }

    public function showFile($id)
    {
        $file = FileRepo::findOrFail($id);

        // Normalisasi path
        $path = str_replace('\\', '/', $file->path);
        $path = preg_replace('#/+#', '/', $path);
        $path = str_replace('public/', '', $path);
        if (! str_contains($path, 'tambahan_file/')) {
            $path = str_replace('tambahan_file', 'tambahan_file/', $path);
        }

        $fullPath = public_path('storage/'.$path);

        if (! file_exists($fullPath)) {
            return redirect()->back()->with('error', 'Tidak ada file untuk diunduh.');
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$file->nama_file.'"',
            ]);
        } else {
            return response()->download($fullPath, $file->nama_file);
        }
    }

    public function destroyFile($id)
    {
        try {
            $file = FileRepo::with('repositori')->findOrFail($id);
            $repo = $file->repositori;

            // Check permissions
            if (auth()->user()->role !== 'super_admin' && $repo->user_id !== auth()->id()) {
                return redirect()->back()->with('error', 'Anda Tidak Punya Akses');
            }

            // Delete file from storage
            $path = 'repositori/'.$repo->id.'/'.$file->nama_file;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Delete record from database
            $file->delete();

            return back()->with('success', 'File Berhasil Di Hapus');

        } catch (\Exception $e) {
            \Log::error('Error deleting file: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus file');
        }
    }
}
