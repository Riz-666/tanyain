<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\FileRepo;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest'); // default "latest"

        if (Auth::check()) {
            $query = Repositori::with('user')->withCount('fileRepo')->addSelect(
                DB::raw('(
                SELECT COUNT(dl.id)
                FROM download_logs dl
                JOIN file_repo fr ON dl.file_repo_id = fr.id
                WHERE fr.repositori_id = repositori.id
            ) as downloads_count'),
            );
        } else {
            $query = Repositori::with('user', 'artikel')
                ->withCount('fileRepo')
                ->addSelect(
                    DB::raw('(
                SELECT COUNT(dl.id)
                FROM download_logs dl
                JOIN file_repo fr ON dl.file_repo_id = fr.id
                WHERE fr.repositori_id = repositori.id
            ) as downloads_count'),
                )
                ->where('status', 'publik');
        }

        // filter search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_repo', 'like', "%{$search}%")->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // filter sort
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('downloads_count');
                break;
            case 'title':
                $query->orderBy('judul_repo', 'asc');
                break;
            case 'author':
                $query->join('users', 'repositori.user_id', '=', 'users.id')->orderBy('users.nama', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $repo = $query->paginate(6)->appends([
            'search' => $search,
            'sort' => $sort,
        ]);

        $totalRepo = Repositori::count();

        $topContributors = \App\Models\User::withCount('repositori')->where('role', 'user')->orderByDesc('repositori_count')->take(3)->get();

        if (Auth::check()) {
            $popularRepos = Repositori::select('repositori.*')
                ->addSelect(
                    DB::raw('(
                SELECT COUNT(dl.id)
                FROM download_logs dl
                JOIN file_repo fr ON dl.file_repo_id = fr.id
                WHERE fr.repositori_id = repositori.id
            ) as downloads_count'),
                )
                ->orderByDesc('downloads_count')
                ->take(3)
                ->get();
        } else {
            $popularRepos = Repositori::select('repositori.*')
                ->addSelect(
                    DB::raw('(
                SELECT COUNT(dl.id)
                FROM download_logs dl
                JOIN file_repo fr ON dl.file_repo_id = fr.id
                WHERE fr.repositori_id = repositori.id
            ) as downloads_count'),
                )
                ->where('status', 'publik')
                ->orderByDesc('downloads_count')
                ->take(3)
                ->get();
        }

        return view('user.repositori', [
            'repo' => $repo,
            'totalRepo' => $totalRepo,
            'search' => $search,
            'sort' => $sort,
            'topContributors' => $topContributors,
            'popularRepos' => $popularRepos,
        ]);
    }

    public function create_repo()
    {
        return view('user.repositori.create');
    }

    public function add_repo(Request $request)
    {
        $request->validate([
            'judul_repo' => 'required|string',
            'deskripsi' => 'nullable',
            'status' => 'required|in:publik,private',
            'file_tambahan' => 'required',
            'file_tambahan.*' => 'file|max:256000',
        ], [
            'file_tambahan.required' => 'Minimal harus ada 1 file yang diupload.',
        ]);

        $allowedExt = ['pdf','jpg', 'jpeg', 'png', 'mp4', 'pptx'];

        $maxSizes = [
            'pdf' => 20 * 1024 * 1024,
            'jpg' => 5 * 1024 * 1024,
            'jpeg' => 5 * 1024 * 1024,
            'png' => 5 * 1024 * 1024,
            'mp4' => 50 * 1024 * 1024,
            'pptx' => 20 * 1024 * 1024,
        ];

        // Validasi ekstensi
        foreach ($request->file('file_tambahan') as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (! in_array($ext, $allowedExt)) {
                return back()
                    ->withErrors(['file_tambahan' => "Tipe file .$ext tidak didukung."])
                    ->withInput();
            }
        }

        // Validasi ukuran per file & total
        $totalUkuran = 0;
        if ($request->hasFile('file_tambahan')) {
            foreach ($request->file('file_tambahan') as $file) {
                $ekstensi = strtolower($file->getClientOriginalExtension());
                $ukuran = $file->getSize();

                if (! isset($maxSizes[$ekstensi])) {
                    return back()
                        ->withErrors(['file_tambahan' => 'Tipe file '.$ekstensi.' tidak didukung.'])
                        ->withInput();
                }

                if ($ukuran > $maxSizes[$ekstensi]) {
                    return back()
                        ->withErrors(['file_tambahan' => 'Ukuran file '.$file->getClientOriginalName().' melebihi batas maksimum untuk tipe '.$ekstensi.'.'])
                        ->withInput();
                }

                $totalUkuran += $ukuran;
            }
        }

        if ($totalUkuran > 250 * 1024 * 1024) {
            return back()
                ->withErrors(['file_tambahan' => 'Total ukuran file melebihi kapasitas maksimum repositori 250MB'])
                ->withInput();
        }

        // ✅ VALIDASI PANJANG NAMA FILE TERLEBIH DAHULU — SEBELUM BUAT REPO!
        if ($request->hasFile('file_tambahan')) {
            foreach ($request->file('file_tambahan') as $file) {
                $namaAsli = $file->getClientOriginalName();
                // Batasi panjang nama file (200 karakter aman untuk path lengkap nanti)
                if (strlen($namaAsli) > 200) {
                    return back()
                        ->withErrors(['file_tambahan' => 'Maaf, nama file terlalu panjang. Silakan rename file Anda dan coba lagi.'])
                        ->withInput();
                }
            }
        }

        // ✅ BARU DI SINI KITA BUAT REPO — SETELAH SEMUA VALIDASI LULUS!
        $repositori = Repositori::create([
            'user_id' => auth()->id(),
            'judul_repo' => $request->judul_repo,
            'deskripsi' => $request->deskripsi ?? '-',
            'status' => $request->status,
        ]);

        // ✅ BARU PROSES UPLOAD FILE
        if ($request->hasFile('file_tambahan')) {
            $folderPath = 'public/repositori/'.$repositori->id;

            foreach ($request->file('file_tambahan') as $file) {
                $namaAsli = $file->getClientOriginalName();
                $ekstensi = $file->getClientOriginalExtension();
                $ukuran = $file->getSize();

                // Generate nama unik
                $namaUnik = Str::random(6).'_'.$namaAsli;

                try {
                    // Simpan file ke folder repo ini
                    $path = $file->storeAs($folderPath, $namaUnik);

                    FileRepo::create([
                        'repositori_id' => $repositori->id,
                        'nama_file' => $namaUnik,
                        'path' => $path,
                        'ekstensi' => $ekstensi,
                        'ukuran' => $ukuran,
                        'created_at' => now(),
                    ]);

                } catch (\Exception $e) {
                    // Jika gagal upload salah satu file, HAPUS SEMUA yang sudah terupload dan hapus repo
                    // Hapus semua file di folder ini
                    if (Storage::disk('public')->exists("repositori/{$repositori->id}")) {
                        Storage::disk('public')->deleteDirectory("repositori/{$repositori->id}");
                    }
                    // Hapus record FileRepo
                    FileRepo::where('repositori_id', $repositori->id)->delete();
                    // Hapus record Repositori
                    $repositori->delete();

                    return back()
                        ->withErrors(['file_tambahan' => 'Terjadi kesalahan saat mengupload file.'])
                        ->withInput();
                }
            }
        }

        // ✅ REDIRECT KE HALAMAN DETAIL
        return redirect()
            ->route('repo.detail', ['id' => $repositori->id])
            ->with('success', 'Repositori berhasil dibuat!');
    }

    public function repo_detail($id)
    {
        $repo = Repositori::with(['fileRepo', 'artikel'])
            ->select('repositori.*')
            ->addSelect(
                DB::raw('(
                SELECT COUNT(dl.id)
                FROM download_logs dl
                JOIN file_repo fr ON dl.file_repo_id = fr.id
                WHERE fr.repositori_id = repositori.id
            ) as downloads_count'),
            )
            ->withSum('fileRepo', 'ukuran')
            ->findOrFail($id);

        // Cek akses private
        if ($repo->status === 'private' && ! Auth::check()) {
            return redirect('/')->with('auth', 'Kamu Tidak Punya Akses Ke Halaman Ini!');
        }

        return view('user.repo_detail', [
            'repo' => $repo,
        ]);
    }

    public function edit($id)
    {
        $repo = Repositori::with('fileRepo')->findOrFail($id);

        if ($repo->user_id !== auth()->id()) {
            return redirect()->route('index')->with('auth', 'Akses ditolak: kamu bukan pemilik repositori ini.');
        }

        return view('user.repositori.edit', compact('repo'));
    }

    public function update(Request $request, $id)
    {
        // Validasi utama — sama persis seperti add_repo()
        $request->validate([
            'judul_repo' => 'required|string',
            'deskripsi' => 'nullable',
            'status' => 'required|in:publik,private',
            'file_tambahan' => 'required',
            'file_tambahan.*' => 'file|max:256000',
        ], [
            'file_tambahan.required' => 'Minimal harus ada 1 file yang diupload.',
        ]);

        // Daftar ekstensi yang diizinkan
        $allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'mp4'];

        // Ukuran maksimal per ekstensi
        $maxSizes = [
            'pdf' => 20 * 1024 * 1024,
            'jpg' => 5 * 1024 * 1024,
            'jpeg' => 5 * 1024 * 1024,
            'png' => 5 * 1024 * 1024,
            'mp4' => 50 * 1024 * 1024,
            'pptx' => 20 * 1024 * 1024,
        ];

        // Ambil repositori yang akan di-edit
        $repo = Repositori::with('fileRepo')->findOrFail($id);

        // Pastikan user adalah pemilik repositori
        if ($repo->user_id !== auth()->id()) {
            return redirect()->route('index')->with('auth', 'Akses ditolak: kamu bukan pemilik repositori ini.');
        }

        // Hitung total ukuran file lama
        $totalUkuranLama = FileRepo::where('repositori_id', $repo->id)->sum('ukuran');
        $totalUkuranBaru = 0;

        // Validasi tipe file dan ukuran per file (baru)
        if ($request->hasFile('file_tambahan')) {
            foreach ($request->file('file_tambahan') as $file) {
                $ekstensi = strtolower($file->getClientOriginalExtension());

                // Cek ekstensi
                if (! in_array($ekstensi, $allowedExt)) {
                    return back()
                        ->withErrors(['file_tambahan' => "Tipe file .$ekstensi tidak didukung."])
                        ->withInput();
                }

                // Cek ukuran per file
                $ukuran = $file->getSize();
                if (! isset($maxSizes[$ekstensi])) {
                    return back()
                        ->withErrors(['file_tambahan' => 'Tipe file '.$ekstensi.' tidak didukung.'])
                        ->withInput();
                }

                if ($ukuran > $maxSizes[$ekstensi]) {
                    return back()
                        ->withErrors(['file_tambahan' => 'Ukuran file '.$file->getClientOriginalName().' melebihi batas maksimum ('.number_format($maxSizes[$ekstensi] / 1024 / 1024, 1).'MB) untuk tipe '.$ekstensi.'.'])
                        ->withInput();
                }

                $totalUkuranBaru += $ukuran;
            }
        }

        // Total keseluruhan: file lama + file baru
        if ($totalUkuranLama + $totalUkuranBaru > 250 * 1024 * 1024) {
            return back()
                ->withErrors(['file_tambahan' => 'Total ukuran file melebihi kapasitas maksimum repositori 250MB'])
                ->withInput();
        }

        // 🔄 PERBARUI DATA REPO UTAMA
        $repo->judul_repo = $request->judul_repo;
        $repo->deskripsi = $request->deskripsi ?? '-';
        $repo->status = $request->status;
        $repo->save();

        // 🚀 SIMPAN FILE BARU — KE FOLDER BERDASARKAN ID REPO
        if ($request->hasFile('file_tambahan')) {
            $folderPath = 'public/repositori/'.$repo->id; // <-- KUNCI PERUBAHAN!

            foreach ($request->file('file_tambahan') as $file) {
                $nama = $file->getClientOriginalName();
                $ekstensi = $file->getClientOriginalExtension();
                $ukuran = $file->getSize();
                $namaUnik = Str::random(6).'_'.$nama;

                // Simpan ke folder khusus repo ini
                $path = $file->storeAs($folderPath, $namaUnik);

                FileRepo::create([
                    'repositori_id' => $repo->id,
                    'nama_file' => $namaUnik,
                    'path' => $path,
                    'ekstensi' => $ekstensi,
                    'ukuran' => $ukuran,
                    'created_at' => now(),
                ]);
            }
        }

        // ✅ REDIRECT KE HALAMAN DETAIL REPO SETELAH DIPERBARUI
        return redirect()
            ->route('repo.detail', ['id' => $repo->id])
            ->with('success', 'Repositori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $repo = Repositori::with('fileRepo', 'artikel')->findOrFail($id);

        if ($repo->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda bukan pemilik repositori ini.',
            ], 403);
        }

        foreach ($repo->fileRepo as $file) {
            $oldPath = 'repositori/'.$repo->id.'/'.$file->nama_file;
            $file->update(['original_path' => $oldPath]);

            $trashFileName = $repo->id.'_'.$file->nama_file;
            $trashPath = 'repositori/trash/'.$trashFileName;

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->makeDirectory('repositori/trash', 0755, true);
                Storage::disk('public')->move($oldPath, $trashPath);
                $file->update(['path' => $trashPath]);
            }
        }

        // ✅ HAPUS FOLDER ASLI SETELAH SEMUA FILE DIPINDAH
        $folderPath = 'repositori/'.$repo->id;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        $repo->deleted_until = now()->addDays(20);
        $repo->save();
        $repo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Repositori berhasil dihapus sementara. Bisa di-restore dalam 20 hari.',
        ]);
    }

    public function restore($id)
    {
        $repo = Repositori::withTrashed()->with('fileRepo')->findOrFail($id);

        if ($repo->user_id !== auth()->id()) {
            return back()->with('error', 'Akses ditolak: Anda bukan pemilik repositori ini.');
        }

        $repo->restore();
        $repo->deleted_until = null;
        $repo->save();

        foreach ($repo->fileRepo as $file) {
            $trashPath = $file->path;
            $originalPath = $file->original_path ?? 'repositori/'.$repo->id.'/'.$file->nama_file;

            // ✅ BUAT FOLDER BARU SEBELUM PINDAH FILE
            $folderPath = dirname($originalPath);
            Storage::disk('public')->makeDirectory($folderPath, 0755, true, true);

            if (Storage::disk('public')->exists($trashPath)) {
                Storage::disk('public')->move($trashPath, $originalPath);
                $file->update([
                    'path' => $originalPath,
                    'original_path' => null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Repositori berhasil dikembalikan beserta semua file.');
    }

    public function forceDelete($id)
    {
        $repo = Repositori::withTrashed()->with('fileRepo', 'artikel')->findOrFail($id);

        // ✅ Cek kepemilikan
        if ($repo->user_id !== auth()->id()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda bukan pemilik repositori ini.',
                ], 403);
            }

            return back()->with('error', 'Akses ditolak: Anda bukan pemilik repositori ini.');
        }

        // Lepas relasi artikel
        foreach ($repo->artikel as $artikel) {
            $artikel->update(['repositori_id' => null]);
        }

        // Hapus file dari trash
        foreach ($repo->fileRepo as $file) {
            $filePath = $file->path; // bisa di trash atau di folder asli, tergantung status
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        // Hapus record file repo
        $repo->fileRepo()->forceDelete();

        // Hapus folder repo jika ada (opsional)
        $repoFolderPath = 'repositori/'.$repo->id;
        if (Storage::disk('public')->exists($repoFolderPath)) {
            Storage::disk('public')->deleteDirectory($repoFolderPath);
        }

        // Hapus repositori permanen
        $repo->forceDelete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Repositori berhasil dihapus permanen!',
            ]);
        }

        return back()->with('success', 'Repositori berhasil dihapus permanen.');
    }
}
