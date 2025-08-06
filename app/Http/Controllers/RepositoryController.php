<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\FileRepo;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RepositoryController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $repo = Repositori::latest()->withCount('fileRepo')->paginate(5);
        } else {
            $repo = Repositori::with('user', 'artikel')->withCount('fileRepo')->where('status', 'publik')->latest()->paginate(5);
        }

        $totalRepo = Repositori::count();
        return view('repository', [
            'repo' => $repo,
            'totalRepo' => $totalRepo,
        ]);
    }

    public function create_repo()
    {
        return view('create_repositori');
    }

    public function add_repo(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'nullable|string',
            'status' => 'required|in:publik,private',
            'file_tambahan.*' => 'nullable|mimes:pdf,doc,docx,zip,xlsx,jpg,jpeg,png,gif|max:1048576',
        ]);

        $repositori = Repositori::create([
            'user_id' => auth()->id(),
            'judul_repo' => $request->judul,
            'deskripsi' => $request->isi,
            'status' => $request->status,
        ]);

        if ($request->hasFile('file_tambahan')) {
            foreach ($request->file('file_tambahan') as $fileTambahan) {
                $nama = time() . '_' . $fileTambahan->getClientOriginalName();
                $tipe = $fileTambahan->getClientOriginalExtension();
                $ukuran = $fileTambahan->getSize();
                $fileTambahan->storeAs('public/repositori/tambahan_file', $nama);

                FileRepo::create([
                    'repositori_id' => $repositori->id,
                    'nama_file' => $nama,
                    'path' => 'public/repositori/tambahan_file/' . $nama,
                    'ekstensi' => $tipe,
                    'ukuran' => $ukuran,
                ]);
            }
        }

        return redirect()->route('repository')->with('success', 'Repositori berhasil ditambahkan.');
    }

    public function repo_detail($id)
    {
        $repo = Repositori::with('fileRepo', 'artikel')->findOrFail($id);

        return view('repositori_detail', [
            'repo' => $repo,
        ]);
    }

    public function edit($id)
    {
        $repo = Repositori::with('fileRepo')->findOrFail($id);
        if ($repo->user_id !== auth()->id()) {
            return redirect()->route('index')->with('auth', 'Akses ditolak: kamu bukan pemilik repositori ini.');
        }
        return view('edit_repositori', compact('repo'));
    }

    public function update(Request $request, $id)
    {
        $repo = Repositori::findOrFail($id);
        $repo->judul_repo = $request->judul;
        $repo->deskripsi = $request->isi;
        $repo->status = $request->status;
        $repo->save();

        if ($request->hasFile('file_tambahan')) {
            foreach ($request->file('file_tambahan') as $file) {
                $nama = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $path = $file->storeAs('repositori/tambahan_file', $nama, 'public');

                FileRepo::create([
                    'repositori_id' => $repo->id,
                    'nama_file' => $nama,
                    'path' => $path,
                    'ekstensi' => $ext,
                    'ukuran' => $size,
                ]);
            }
        }

        return redirect()->route('repo.detail', $repo->id)->with('success', 'Repository Berhasil Di Perbarui');
    }

    public function destroy($id)
    {
        $repo = Repositori::with('fileRepo')->findOrFail($id);

        foreach ($repo->fileRepo as $file) {
            $path = 'repositori/tambahan_file/' . $file->nama_file;
            if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
        }
        FileRepo::where('repositori_id', $repo->id)->delete();

        Artikel::where('repositori_id', $repo->id)->delete();

        $repo->delete();

        return redirect()->back()->with('success', 'Repositori dan seluruh datanya berhasil dihapus.');
    }
}
