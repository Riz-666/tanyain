<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile(string $id, Request $request)
    {
        $query = $request->input('q', '');
        $filter = $request->input('filter', 'all');
        $user = User::with(['artikel', 'repositori'])->findOrFail($id);

        // Default query
        $artikelQuery = Artikel::where('user_id', $user->id)->with('tag')->orderBy('created_at', 'desc');
        $reposQuery = Repositori::where('user_id', $user->id)->orderBy('created_at', 'desc');

        // Jika user belum login, batasi ke publik saja
        if (!Auth::check()) {
            $artikelQuery->where('status', 'publik');
            $reposQuery->where('status', 'publik');
        } else {
            // User login → filter sesuai pilihan
            if ($filter === 'publik') {
                $artikelQuery->where('status', 'publik');
                $reposQuery->where('status', 'publik');
            } elseif ($filter === 'private') {
                $artikelQuery->where('status', 'private');
                $reposQuery->where('status', 'private');
            }
            // 'all' → tampil semua
        }

        // Search query tetap jalan
        if ($query) {
            $artikelQuery->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%$query%")->orWhere('isi', 'like', "%$query%");
            });

            $reposQuery->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%$query%")->orWhere('deskripsi', 'like', "%$query%");
            });
        }

        // Pagination
        $artikel = $artikelQuery->paginate(5)->appends($request->all());
        $repositori = $reposQuery->paginate(5)->appends($request->all());

        // Statistik
        $stats = [
            'artikel' => $user->artikel()->count(),
            'repositori' => $user->repositori()->count(),
            'views' => \DB::table('view_artikel')->join('artikel', 'view_artikel.artikel_id', '=', 'artikel.id')->where('artikel.user_id', $user->id)->count(),
            'files' => \DB::table('file_repo')->join('repositori', 'file_repo.repositori_id', '=', 'repositori.id')->where('repositori.user_id', $user->id)->count(),
        ];

        $activeTab = $request->query('tab', 'articles'); // tab aktif

        return view('user.profile', compact('user', 'artikel', 'repositori', 'stats', 'activeTab', 'query', 'filter'));
    }

    public function edit_profile(string $id)
    {
        $user = User::findOrFail($id);

        // HANYA BOLEH EDIT PROFIL SENDIRI
        if (auth()->id() !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit profil ini.');
        }

        return view('user.edit-profile', ['user' => $user]);
    }

    public function update_profile(Request $request, string $id)
    {
        // Ambil user yang akan di-edit
        $user = User::findOrFail($id);

        // Hanya boleh edit profil sendiri
        if (auth()->id() !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit profil ini.');
        }

        // Validasi data
        $rules = [
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:30|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'currentPassword' => 'required|string',
        ];

        // Jika password baru diisi, tambahkan validasi
        if ($request->filled('password')) {
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

        // Validasi input
        $request->validate($rules);

        // Verifikasi password lama
        if (!Hash::check($request->currentPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['currentPassword' => 'Password saat ini salah.'])
                ->withInput();
        }

        // Update data dasar
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Simpan ke storage
            $file->storeAs('public/user-img', $filename);

            // Hapus foto lama jika bukan default
            if ($user->foto && $user->foto !== 'default-user.jpg') {
                Storage::delete('public/user-img/' . $user->foto);
            }

            $user->foto = $filename;
        }

        // Tambahan: update bio & sosmed
        $user->bio = $request->bio;
        $user->github = $request->github;
        $user->instagram = $request->instagram;
        $user->linkedin = $request->linkedin;

        // Simpan perubahan
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('profile.edit', $user->id)->with('success', 'Profile berhasil diperbarui!');
    }
    public function destroyPhoto(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Pastikan hanya user sendiri yang bisa hapus
        if (auth()->id() !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin.'], 403);
        }

        // Jika ada foto lama dan bukan default
        if ($user->foto && $user->foto !== 'default-user.jpg') {
            // Hapus file dari storage
            Storage::delete('public/user-img/' . $user->foto);
        }

        // Kosongkan field foto di database
        $user->foto = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }
}
