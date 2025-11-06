<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Komentar;
use App\Models\Repositori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function user()
    {
        $user = User::where('role', 'user')->orderByRaw('updated_at IS NULL, updated_at DESC, created_at DESC')->get();

        return view('admin.user.user', [
            'user' => $user,
        ]);
    }

    public function create()
    {
        return view('admin.user.create', []);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value !== $request->confirm_password) {
                        $fail('Password dan konfirmasi password tidak cocok.');
                    }
                },
            ],
        ]);
        $username = $request->username;
        while (User::where('username', $username)->exists()) {
            $firstWord = strtolower(explode(' ', $request->nama)[0]);
            $randomNum = rand(1000, 9999);
            $username = $firstWord.$randomNum;
        }

        User::create([
            'nama' => $request->nama,
            'username' => $username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
        ];

        if ($request->password) {
            $rules['password'] = [
                'string',
                'min:8',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value !== $request->confirm_password) {
                        $fail('Password dan konfirmasi password tidak cocok.');
                    }
                },
            ];
        }

        $request->validate($rules);

        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui!');
    }

    public function trashUser()
    {
        $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        // Hitung jumlah sampah dari masing-masing model
        $trashedUsersCount = $users->count();
        $trashedArticlesCount = Artikel::onlyTrashed()->count();
        $trashedReposCount = Repositori::onlyTrashed()->count(); // Sesuaikan nama model

        return view('admin.user.trash', [
            'type' => 'user',
            'data' => $users,
            'trashedUsersCount' => $trashedUsersCount,
            'trashedArticlesCount' => $trashedArticlesCount,
            'trashedReposCount' => $trashedReposCount,
        ]);
    }

public function destroy(User $user)
{
    Storage::makeDirectory('public/trash/artikel');
    Storage::makeDirectory('public/profile-trash');

    // ======================
    // ARTIKEL
    // ======================
    foreach ($user->artikel as $artikel) {
        Komentar::where('artikel_id', $artikel->id)->delete();

        if ($artikel->file && Storage::disk('public')->exists("artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/files", 0755, true);
            Storage::disk('public')->move(
                "artikel/{$artikel->id}/files/{$artikel->file}",
                "trash/artikel/{$artikel->id}/files/{$artikel->file}"
            );
        }

        if ($artikel->cover && Storage::disk('public')->exists("artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/cover", 0755, true);
            Storage::disk('public')->move(
                "artikel/{$artikel->id}/cover/{$artikel->cover}",
                "trash/artikel/{$artikel->id}/cover/{$artikel->cover}"
            );
        }

        if (!empty($artikel->isi)) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
            foreach ($matches[1] as $imgUrl) {
                $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    $trashPath = 'trash/'.$path;
                    Storage::disk('public')->makeDirectory(dirname($trashPath), 0755, true);
                    Storage::disk('public')->move($path, $trashPath);
                }
            }
        }

        $artikel->deleted_at = now();
        $artikel->deleted_until = now()->addDays(20);
        $artikel->save();

        // ✅ HAPUS FOLDER ID ARTIKEL
        $folderPath = 'artikel/' . $artikel->id;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
    }

    // ======================
    // REPOSITORI
    // ======================
    foreach ($user->repositori as $repo) {
        foreach ($repo->fileRepo as $file) {
            $oldPath = "repositori/{$repo->id}/{$file->nama_file}";
            $trashFileName = $repo->id.'_'.$file->nama_file;
            $trashPath = "repositori/trash/{$trashFileName}";

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $trashPath);
            }

            $file->deleted_at = now();
            $file->deleted_until = now()->addDays(20);
            $file->save();
        }

        $repo->deleted_at = now();
        $repo->deleted_until = now()->addDays(20);
        $repo->save();

        // ✅ HAPUS FOLDER ID REPOSITORI
        $folderPath = 'repositori/' . $repo->id;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
    }

    // ======================
    // FOTO PROFIL USER
    // ======================
    if (!empty($user->foto)) {
        $fotoPath = "user-img/{$user->foto}";
        $trashPath = "profile-trash/{$user->foto}";

        if (Storage::disk('public')->exists($fotoPath)) {
            Storage::disk('public')->move($fotoPath, $trashPath);
        }
    }

    // ======================
    // USER
    // ======================
    $user->deleted_at = now();
    $user->deleted_until = now()->addDays(20);
    $user->save();

    return redirect()->route('admin.user')->with('success', 'User moved to trash.');
}

public function restore($userId)
{
    $user = User::withTrashed()
        ->with(['artikel', 'repositori.fileRepo'])
        ->findOrFail($userId);

    // Pastikan folder tujuan ada
    $paths = [
        'public/artikel',
        'public/repositori',
        'public/user-img',
    ];
    foreach ($paths as $path) {
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }
    }

    // Restore artikel
    foreach ($user->artikel()->withTrashed()->get() as $artikel) {
        // ✅ Pulihkan file utama
        if ($artikel->file && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/files", 0755, true);
            Storage::disk('public')->move(
                "trash/artikel/{$artikel->id}/files/{$artikel->file}",
                "artikel/{$artikel->id}/files/{$artikel->file}"
            );
        }

        // ✅ Pulihkan cover
        if ($artikel->cover && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/cover", 0755, true);
            Storage::disk('public')->move(
                "trash/artikel/{$artikel->id}/cover/{$artikel->cover}",
                "artikel/{$artikel->id}/cover/{$artikel->cover}"
            );
        }

        // ✅ Pulihkan gambar dari isi artikel
        if (!empty($artikel->isi)) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
            foreach ($matches[1] as $imgUrl) {
                $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                $trashPath = 'trash/' . $path;
                if (Storage::disk('public')->exists($trashPath)) {
                    Storage::disk('public')->makeDirectory(dirname($path), 0755, true);
                    Storage::disk('public')->move($trashPath, $path);
                }
            }
        }

        $artikel->deleted_at = null;
        $artikel->deleted_until = null;
        $artikel->save();

        // ✅ HAPUS HANYA FOLDER TRASH UNTUK ARTIKEL INI
        $trashFolder = "trash/artikel/{$artikel->id}";
        if (Storage::disk('public')->exists($trashFolder)) {
            Storage::disk('public')->deleteDirectory($trashFolder);
        }
    }

    // Restore repositori — TIDAK DIUBAH
    foreach ($user->repositori()->withTrashed()->get() as $repo) {
        foreach ($repo->fileRepo()->withTrashed()->get() as $file) {
            $trashFileName = $repo->id.'_'.$file->nama_file;
            $trashPath = "repositori/trash/{$trashFileName}";
            $originalPath = "repositori/{$repo->id}/{$file->nama_file}";

            if (Storage::disk('public')->exists($trashPath)) {
                Storage::disk('public')->makeDirectory("repositori/{$repo->id}", 0755, true);
                Storage::disk('public')->move($trashPath, $originalPath);
            }

            $file->deleted_at = null;
            $file->deleted_until = null;
            $file->save();
        }

        $repo->deleted_at = null;
        $repo->deleted_until = null;
        $repo->save();
    }

    // Restore foto profil — TIDAK DIUBAH
    if ($user->foto && Storage::disk('public')->exists("profile-trash/{$user->foto}")) {
        Storage::disk('public')->makeDirectory('user-img', 0755, true);
        Storage::disk('public')->move("profile-trash/{$user->foto}", "user-img/{$user->foto}");
    }

    // Restore user — TIDAK DIUBAH
    $user->deleted_at = null;
    $user->deleted_until = null;
    $user->save();

    return redirect()->route('admin.trash.user')->with('success', 'Data Pengguna berhasil dipulihkan.');
}

    public function forceDelete($userId)
    {
        $user = User::withTrashed()
            ->with(['artikel', 'repositori.fileRepo'])
            ->findOrFail($userId);

        // ✅ Hapus semua file dari trash artikel
        foreach ($user->artikel()->onlyTrashed()->get() as $artikel) {
            $trashFolder = "trash/artikel/{$artikel->id}";
            if (Storage::disk('public')->exists($trashFolder)) {
                Storage::disk('public')->deleteDirectory($trashFolder);
            }
        }

        // ✅ Hapus semua file dari trash repositori
        foreach ($user->repositori()->onlyTrashed()->get() as $repo) {
            foreach ($repo->fileRepo()->withTrashed()->get() as $file) {
                $trashFileName = $repo->id.'_'.$file->nama_file;
                $trashPath = "repositori/trash/{$trashFileName}";
                if (Storage::disk('public')->exists($trashPath)) {
                    Storage::disk('public')->delete($trashPath);
                }
            }
        }

        // ✅ Hapus foto profil dari trash
        if ($user->foto && Storage::disk('public')->exists("profile-trash/{$user->foto}")) {
            Storage::disk('public')->delete("profile-trash/{$user->foto}");
        }

        // ✅ Putuskan relasi untuk artikel & repo yang masih aktif
        $user->artikel()->whereNull('deleted_at')->update(['user_id' => null]);
        $user->repositori()->whereNull('deleted_at')->update(['user_id' => null]);

        // ✅ Force delete artikel & repo yang di-trash
        $user->artikel()->onlyTrashed()->forceDelete();
        foreach ($user->repositori()->onlyTrashed()->get() as $repo) {
            $repo->fileRepo()->withTrashed()->forceDelete();
            $repo->forceDelete();
        }

        // ✅ Force delete user
        $user->forceDelete();

        return redirect()->route('admin.trash.user')->with('success', 'Data Pengguna dihapus permanen. Artikel/repositori yang sudah direstore tetap ada dan menjadi konten tanpa pemilik.');
    }
}
