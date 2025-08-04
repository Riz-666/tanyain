<?php

namespace App\Http\Controllers;

use App\Models\FileRepo;
use App\Models\Repositori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
