<?php

namespace App\Http\Controllers;

use App\Models\DownloadLog;
use App\Models\FileRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileRepoController extends Controller
{
    public function index(Request $request)
    {
        $extension = $request->input('extension', 'all');
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'latest');

        // === 1. Query dasar dengan filter akses ===
        $baseQuery = function () {
            return FileRepo::query()
                ->whereNotNull('ekstensi')
                ->whereHas('repositori', function ($q) {
                    $q->whereNull('deleted_at'); // pastikan repo tidak dihapus
                });
        };

        // === 2. Filter akses berdasarkan login ===
        $applyAccessFilter = function ($query) {
            if (!auth()->check()) {
                // Guest: hanya repositori publik
                $query->whereHas('repositori', function ($q) {
                    $q->where('status', 'publik');
                });
            }
            // Jika login (user biasa atau admin), tampilkan SEMUA file
            // → karena user login boleh lihat private (nanti di route file akan dicek aksesnya)
            // Tapi untuk konsistensi UX, kita tampilkan semua ekstensi saat login.
        };

        // === 3. Ambil daftar ekstensi (untuk filter atas) ===
        $extensionsQuery = $baseQuery();
        $applyAccessFilter($extensionsQuery);
        $extensions = $extensionsQuery->distinct()->pluck('ekstensi');

        // === 4. Query utama file ===
        $filesQuery = FileRepo::with(['repositori.user', 'user'])
            ->withCount('downloadLogs')
            ->whereHas('repositori', function ($q) {
                $q->whereNull('deleted_at');
            });
        $applyAccessFilter($filesQuery);

        // Filter ekstensi
        if ($extension !== 'all') {
            $filesQuery->where('ekstensi', $extension);
        }

        // Filter search
        if (!empty($search)) {
            $filesQuery->where(function ($q) use ($search) {
                $q->where('nama_file', 'like', "%{$search}%")
                    ->orWhere('ekstensi', 'like', "%{$search}%")
                    ->orWhereHas('repositori', function ($qr) use ($search) {
                        $qr->where('judul_repo', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        switch ($sort) {
            case 'name':
                $filesQuery->orderBy('nama_file', 'asc');
                break;
            case 'size':
                $filesQuery->orderBy('ukuran', 'desc');
                break;
            case 'extension':
                $filesQuery->orderBy('ekstensi', 'asc');
                break;
            case 'downloads':
                $filesQuery->orderBy('download_logs_count', 'desc');
                break;
            default:
                $filesQuery->orderBy('created_at', 'desc');
                break;
        }

        $files = $filesQuery->paginate(6)->appends([
            'extension' => $extension,
            'search' => $search,
            'sort' => $sort,
        ]);

        // === 5. Ekstensi + count (sidebar) ===
        $extensionsCountQuery = FileRepo::select('ekstensi')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('ekstensi')
            ->whereHas('repositori', function ($q) {
                $q->whereNull('deleted_at');
            });
        $applyAccessFilter($extensionsCountQuery);
        $extensionsCount = $extensionsCountQuery->groupBy('ekstensi')->orderByDesc('total')->get();

        // === 6. File populer ===
        $popularFilesQuery = FileRepo::withCount('downloadLogs')
            ->orderByDesc('download_logs_count')
            ->take(5)
            ->whereHas('repositori', function ($q) {
                $q->whereNull('deleted_at');
            });
        $applyAccessFilter($popularFilesQuery);
        $popularFiles = $popularFilesQuery->get();
        $selectedExtension = $extension;

        return view('user.file', compact('files', 'extensions', 'extensionsCount', 'popularFiles', 'selectedExtension', 'search'));
    }

    public function showPdf($id)
    {
        $file = FileRepo::with('repositori')->findOrFail($id);
        $repo = $file->repositori;

        // Jika private, cukup cek sudah login atau belum
        if ($repo && $repo->status === 'private') {
            if (!auth()->check()) {
                return redirect()->back()->with('auth', 'Anda harus login untuk melihat file ini.');
            }
            // Kalau sudah login, siapapun boleh akses (admin/user biasa)
        }

        // Lanjutkan proses file
        $relativePath = str_replace('public/', '', ltrim($file->path, '/\\'));
        $fullPath = Storage::disk('public')->path($relativePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return redirect()->back()->with('errorFile', 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'])) {
            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . basename($file->nama_file) . '"',
            ]);
        }

        if ($extension === 'pdf') {
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($file->nama_file) . '"',
            ]);
        }

        return response()->download($fullPath, $file->nama_file);
    }
    public function showFile($id)
    {
        $file = FileRepo::with('repositori')->findOrFail($id);
        $repo = $file->repositori;

        // Kalau repo private, cek login dulu
        if ($repo && $repo->status === 'private') {
            if (!auth()->check()) {
                return redirect()->back()->with('auth', 'Anda harus login untuk mengunduh file ini.');
            }
            // Sudah login? Boleh akses
        }

        $relativePath = str_replace('public/', '', ltrim($file->path, '/\\'));
        $fullPath = Storage::disk('public')->path($relativePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return redirect()->back()->with('errorFile', 'Tidak ada file untuk diunduh.');
        }

        // Log download
        DownloadLog::create([
            'file_repo_id' => $file->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($file->nama_file) . '"',
            ]);
        }

        return response()->download($fullPath, $file->nama_file);
    }

    public function destroy($id)
    {
        $file = FileRepo::with('repositori')->findOrFail($id);

        // Ambil ID repo untuk bangun path folder yang benar
        $repoId = $file->repositori_id;
        $path = 'repositori/' . $repoId . '/' . $file->nama_file;

        // Hapus file fisik jika ada
        if (\Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        } else {
            \Log::warning('File tidak ditemukan saat hapus: ' . $path);
        }

        // Hapus permanen dari database
        $file->forceDelete();

        // Respons sesuai request
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus',
            ]);
        }

        return back()->with('success', 'File Berhasil Di Hapus');
    }
}
