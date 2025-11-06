<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Repositori;
use App\Models\Tag;
use App\Models\User;
use App\Models\viewArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class ArticleController extends Controller
{
    public function article(Request $request)
    {
        $sort = $request->input('sort', 'latest');
        $search = $request->input('search');
        $tag = $request->input('tag');

        $query = Artikel::with(['user', 'repositori', 'viewArtikel', 'tag']);

        if (! Auth::check()) {
            $query->where('status', 'publik');
        }

        // Filter search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                    ->orWhere('isi', 'like', '%'.$search.'%')
                    ->orWhereHas('tag', function ($tagQuery) use ($search) {
                        $tagQuery->where('nama_tag', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama', 'like', '%'.$search.'%');
                    });
            });
        }

        // Filter berdasarkan tag
        if ($request->has('tag') && $request->tag !== 'all') {
            $query->whereHas('tag', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        switch ($sort) {
            case 'popular':
                // Hitung jumlah view dari relasi viewArtikel
                $query->withCount('viewArtikel')->orderByDesc('view_artikel_count');
                break;
            case 'title':
                $query->orderBy('judul', 'asc');
                break;
            case 'author':
                $query->join('users', 'artikel.user_id', '=', 'users.id')
                    ->orderBy('users.nama', 'asc')
                    ->select('artikel.*'); // penting!
                break;
            default:
                $query->latest();
                break;
        }

        $artikel = $query->paginate(6)->appends([
            'sort' => $sort,
            'search' => $search,
            'tag' => $tag,
        ]);

        // Artikel populer berdasarkan jumlah view (top 5)
        $popularArticlesQuery = Artikel::withCount('viewArtikel')->orderByDesc('view_artikel_count')->take(3);

        // Penulis paling produktif (top 3 user dengan artikel terbanyak)
        $topAuthors = User::where('role', 'user')->withCount('artikel')->orderByDesc('artikel_count')->take(3)->get();

        // Tag paling banyak dipakai (top 6)
        $popularTags = Tag::withCount([
            'artikel as artikel_count' => function ($query) {
                if (! auth()->check()) {
                    $query->where('status', 'publik');
                }
            },
        ])
            ->having('artikel_count', '>', 0) // hanya ambil tag yang count > 0
            ->orderByDesc('artikel_count')
            ->take(6)
            ->get();

        // Kalau guest, filter cuma artikel publik
        if (! auth()->check()) {
            $popularArticlesQuery->where('status', 'publik');
        }

        $popularArticles = $popularArticlesQuery->get();

        $totalArtikel = Artikel::count();
        $totalUser = User::where('role', 'user')->count();
        $allTags = Tag::all();

        return view('user.artikel', [
            'artikel' => $artikel,
            'totalArtikel' => $totalArtikel,
            'totalUser' => $totalUser,
            'allTags' => $allTags,
            'sort' => $sort,
            'search' => $search,
            'tag' => $tag,
            'popularArticles' => $popularArticles,
            'topAuthors' => $topAuthors,
            'popularTags' => $popularTags,
        ]);
    }

    public function article_detail(Request $request, string $id)
    {
        $artikel = Artikel::with(['user', 'viewArtikel', 'repositori', 'tag', 'komentar.user', 'komentar.children.user'])->findOrFail($id);

        if ($artikel->status === 'private' && ! Auth::check()) {
            return redirect('/')->with('auth', 'Kamu Tidak Punya Akses Ke Halaman Ini ! ');
        }

        // views/ip
        // views/ip - SAFER VERSION
        $userId = null; // Default null
        $ip = $request->ip();

        if (Auth::check()) {
            // Double check user exists sebelum assign ID
            $authUserId = Auth::id();
            if (User::where('id', $authUserId)->exists()) {
                $userId = $authUserId;
            } else {
                // User udah dihapus, force logout
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        }

        $sudahDilihat = viewArtikel::where('artikel_id', $artikel->id)
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(! $userId, fn ($q) => $q->where('ip_address', $ip))
            ->exists();

        if (! $sudahDilihat) {
            viewArtikel::create([
                'artikel_id' => $artikel->id,
                'user_id' => $userId,
                'user_agent' => $request->header('User-Agent'),
                'ip_address' => $ip,
                'created_at' => now(),
            ]);
        }

        return view('user.artikel_detail', [
            'artikel' => $artikel,
        ]);
    }

    public function create_article()
    {
        $repositori = Repositori::where('user_id', Auth::id())->get();
        $tag = Tag::all();

        return view('user.artikel.create-artikel', [
            'repositori' => $repositori,
            'tag' => $tag,
        ]);
    }

    public function add_artikel(Request $request)
    {
        $validatedData = $request->validate([
            'repositori_id' => 'nullable|exists:repositori,id',
            'judul' => 'required|string',
            'isi' => 'required',
            'tag' => 'nullable|array',
            'tag.*' => 'nullable',
            'file' => 'nullable|mimes:pdf,doc,docx,xlsx,pptx,zip,rar,txt|max:1048576',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'uploaded_images' => 'nullable|string', // ✅ JSON string dari gambar yang diupload
        ]);

        $repo = null;
        $status = 'publik';

        if (! empty($validatedData['repositori_id'])) {
            $repo = Repositori::where('id', $validatedData['repositori_id'])->where('user_id', Auth::id())->first();
            if (! $repo) {
                return back()->withErrors(['repositori_id' => 'Repo tidak valid atau bukan milikmu.']);
            }
            $status = $repo->status;
        }

        // Slug unik
        $slug = Str::slug($validatedData['judul']);
        if (Artikel::where('slug', $slug)->exists()) {
            return redirect()->back()->with('duplicate', 'Artikel dengan judul ini sudah ada.');
        }

        // ✅ BUAT ARTIKEL DULU
        $artikel = Artikel::create([
            'user_id' => auth()->id(),
            'repositori_id' => $validatedData['repositori_id'] ?? null,
            'judul' => $validatedData['judul'],
            'slug' => $slug,
            'isi' => $validatedData['isi'], // Sementara simpan dulu dengan URL temp
            'status' => $status,
            'views' => 0,
            'cover' => null,
            'file' => null,
        ]);

        // 🖼️ PINDAHKAN GAMBAR DARI TEMP KE FOLDER ARTIKEL
        $uploadedImages = json_decode($request->input('uploaded_images', '[]'), true);
        $newContent = $validatedData['isi'];

        if (! empty($uploadedImages)) {
            // ✅ WAJIB: Buat folder images sebelum pindah file
            Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/images", 0755, true);

            foreach ($uploadedImages as $imageInfo) {
                $tempPath = $imageInfo['temp_path']; // artikel/temp/filename.jpg
                $tempUrl = $imageInfo['url']; // /storage/artikel/temp/filename.jpg

                // Path baru di folder artikel
                $filename = basename($tempPath);
                $newPath = "artikel/{$artikel->id}/images/{$filename}";
                $newUrl = "/storage/{$newPath}";

                // Pindahkan file dari temp ke folder artikel
                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);

                    // Update URL di konten
                    $newContent = str_replace($tempUrl, $newUrl, $newContent);
                }
            }
        }

        // ✅ UPDATE KONTEN DENGAN URL BARU DAN PURIFY
        $artikel->update([
            'isi' => Purifier::clean($newContent),
        ]);

        // 🖼️ UPLOAD COVER
        if ($request->hasFile('cover')) {
            $coverName = time().'_'.Str::random(4).'_'.$request->file('cover')->getClientOriginalName();
            $request->file('cover')->storeAs("public/artikel/{$artikel->id}/cover", $coverName);
            $artikel->update(['cover' => $coverName]);
        }

        // 📄 UPLOAD FILE UTAMA
        if ($request->hasFile('file')) {
            $filename = time().'_'.Str::random(4).'_'.$request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs("public/artikel/{$artikel->id}/files", $filename);
            $artikel->update(['file' => $filename]);
        }

        // === Handle Tags ===
        $detectedTags = [];

        if (! empty($validatedData['tag'])) {
            foreach ($validatedData['tag'] as $t) {
                if (is_numeric($t)) {
                    if (Tag::find($t)) {
                        $detectedTags[] = (int) $t;
                    }
                } else {
                    $newTagName = trim($t);
                    if ($newTagName !== '') {
                        $slugTag = Str::slug($newTagName);
                        if ($slugTag !== '') {
                            $tag = Tag::firstOrCreate(['slug' => $slugTag], ['nama_tag' => $newTagName]);
                            $detectedTags[] = $tag->id;
                        }
                    }
                }
            }
        }

        // Auto detect dari judul & isi
        $allTags = Tag::all();
        foreach ($allTags as $tag) {
            if (stripos($validatedData['judul'], $tag->nama_tag) !== false || stripos($validatedData['isi'] ?? '', $tag->nama_tag) !== false) {
                $detectedTags[] = $tag->id;
            }
        }

        $detectedTags = array_unique($detectedTags);

        if (! empty($detectedTags)) {
            $artikel->tag()->syncWithoutDetaching($detectedTags);
        }

        return redirect()->route('article')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:1048576',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'repositori_id' => 'nullable|exists:repositori,id',
            'uploaded_images' => 'nullable|string',
            'tag' => 'nullable|array',
            'tag.*' => 'nullable',
        ]);

        $artikel = Artikel::findOrFail($id);

        if ($artikel->user_id !== auth()->id()) {
            return back()->withErrors(['auth' => 'Akses ditolak: kamu bukan pemilik artikel ini.']);
        }

        // === 1. DETEKSI GAMBAR YANG DIHAPUS ===
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matchesOld);
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $request->isi, $matchesNew);

        $oldImages = $matchesOld[1] ?? [];
        $newImages = $matchesNew[1] ?? [];
        $deletedImages = array_diff($oldImages, $newImages);

        foreach ($deletedImages as $url) {
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // === 2. PINDAHKAN GAMBAR BARU DARI TEMP KE FOLDER ARTIKEL ===
        $uploadedImages = json_decode($request->input('uploaded_images', '[]'), true);
        $newContent = $request->isi;

        if (! empty($uploadedImages)) {
            // ✅ WAJIB: Pastikan folder images ada
            $imageDirectory = "artikel/{$artikel->id}/images";
            if (! Storage::disk('public')->exists($imageDirectory)) {
                Storage::disk('public')->makeDirectory($imageDirectory, 0755, true);
            }

            foreach ($uploadedImages as $imageInfo) {
                // ✅ Hanya proses gambar yang benar-benar dari folder temp
                if (! empty($imageInfo['temp_path']) && strpos($imageInfo['temp_path'], 'artikel/temp/') !== false) {
                    $tempPath = $imageInfo['temp_path'];
                    $tempUrl = $imageInfo['url'];

                    if (Storage::disk('public')->exists($tempPath)) {
                        $filename = basename($tempPath);
                        $newPath = "artikel/{$artikel->id}/images/{$filename}";
                        $newUrl = "/storage/{$newPath}";

                        // ✅ GUNAKAN move() — LEBIH EFISIEN
                        Storage::disk('public')->move($tempPath, $newPath);

                        // ✅ Update URL di konten
                        $newContent = str_replace($tempUrl, $newUrl, $newContent);

                        \Log::info("✅ Gambar dipindah saat update: {$tempPath} -> {$newPath}");
                    }
                }
            }
        }

        // === 3. UPDATE FIELD DASAR ===
        $artikel->judul = $request->judul;
        $artikel->slug = Str::slug($request->judul);
        $artikel->isi = Purifier::clean($newContent);

        // === 4. HANDLE REPOSITORI ===
        if ($request->filled('repositori_id')) {
            $repo = Repositori::where('id', $request->repositori_id)
                ->where('user_id', Auth::id())
                ->first();

            if (! $repo) {
                return back()->withErrors(['repositori_id' => 'Repo tidak valid atau bukan milikmu.']);
            }

            $artikel->repositori_id = $repo->id;
            $artikel->status = $repo->status;
        } else {
            $artikel->repositori_id = null;
            $artikel->status = 'publik';
        }

        // === 5. HANDLE FILE PDF BARU ===
        if ($request->hasFile('file')) {
            if ($artikel->file && Storage::disk('public')->exists("artikel/{$artikel->id}/files/{$artikel->file}")) {
                Storage::disk('public')->delete("artikel/{$artikel->id}/files/{$artikel->file}");
            }

            $filename = time().'_'.$request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs("public/artikel/{$artikel->id}/files", $filename);
            $artikel->file = $filename;
        }

        // === 6. HANDLE COVER BARU ===
        if ($request->hasFile('cover')) {
            if ($artikel->cover && Storage::disk('public')->exists("artikel/{$artikel->id}/cover/{$artikel->cover}")) {
                Storage::disk('public')->delete("artikel/{$artikel->id}/cover/{$artikel->cover}");
            }

            $coverName = time().'_'.$request->file('cover')->getClientOriginalName();
            $request->file('cover')->storeAs("public/artikel/{$artikel->id}/cover", $coverName);
            $artikel->cover = $coverName;
        }

        $artikel->save();

        // === 7. HANDLE TAGS ===
        $detectedTags = [];

        if (! empty($request->input('tag'))) {
            foreach ($request->input('tag') as $t) {
                if (is_numeric($t)) {
                    if (Tag::find($t)) {
                        $detectedTags[] = (int) $t;
                    }
                } else {
                    $newTagName = trim($t);
                    if ($newTagName !== '') {
                        $slugTag = Str::slug($newTagName);
                        if ($slugTag !== '') {
                            $tag = Tag::firstOrCreate(['slug' => $slugTag], ['nama_tag' => $newTagName]);
                            $detectedTags[] = $tag->id;
                        }
                    }
                }
            }
        }

        // Auto detect dari judul & isi
        $allTags = Tag::all();
        foreach ($allTags as $tag) {
            if (stripos($request->judul, $tag->nama_tag) !== false || stripos($request->isi ?? '', $tag->nama_tag) !== false) {
                $detectedTags[] = $tag->id;
            }
        }

        $detectedTags = array_unique($detectedTags);

        if (! empty($detectedTags)) {
            $artikel->tag()->syncWithoutDetaching($detectedTags);
        }

        // ✅ PINDAHKAN KE SINI — SETELAH SEMUA PERUBAHAN
        $artikel->save();

        return redirect()->route('article.detail', $artikel->id)
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function edit(string $id)
    {
        $artikel = Artikel::with('tag')->findOrFail($id);
        $tag = Tag::all();

        if ($artikel->user_id !== auth()->id()) {
            return redirect()->route('index')->with('auth', 'Akses ditolak: kamu bukan pemilik artikel ini.');
        }

        $repositori = Repositori::where('user_id', Auth::id())->get();

        return view('user.artikel.edit-artikel', [
            'artikel' => $artikel,
            'repositori' => $repositori,
            'tag' => $tag,
        ]);
    }

    private function generateTags(Artikel $artikel)
    {
        // Ambil semua tag dari database
        $tags = Tag::all();

        $content = strtolower($artikel->judul.' '.$artikel->isi);

        $tagIds = [];
        foreach ($tags as $tag) {
            if (strpos($content, strtolower($tag->nama_tag)) !== false) {
                $tagIds[] = $tag->id;
            }
        }

        $artikel->tag()->sync($tagIds);
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        if ($artikel->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda bukan pemilik artikel ini.',
            ], 403);
        }

        // Pindah file utama
        if ($artikel->file && Storage::disk('public')->exists("artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/files", 0755, true);
            Storage::disk('public')->move(
                "artikel/{$artikel->id}/files/{$artikel->file}",
                "trash/artikel/{$artikel->id}/files/{$artikel->file}"
            );
        }

        // Pindah cover
        if ($artikel->cover && Storage::disk('public')->exists("artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->makeDirectory("trash/artikel/{$artikel->id}/cover", 0755, true);
            Storage::disk('public')->move(
                "artikel/{$artikel->id}/cover/{$artikel->cover}",
                "trash/artikel/{$artikel->id}/cover/{$artikel->cover}"
            );
        }

        // Pindah gambar dari isi artikel
        if (! empty($artikel->isi)) {
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

        // Soft delete
        $artikel->deleted_until = now()->addDays(20);
        $artikel->save();
        $artikel->delete();

        // ✅ HAPUS FOLDER ASLI SETELAH SEMUA FILE DIPINDAH
        $folderPath = 'artikel/'.$artikel->id;
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        return response()->json([
            'success' => true,
            'message' => 'Artikel masuk trash (20 hari)',
        ]);
    }

    public function restore($id)
    {
        $artikel = Artikel::withTrashed()->findOrFail($id);

        // ✅ Restore file PDF/artikel
        if ($artikel->file && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/files", 0755, true);
            Storage::disk('public')->move(
                "trash/artikel/{$artikel->id}/files/{$artikel->file}",
                "artikel/{$artikel->id}/files/{$artikel->file}"
            );
        }

        // ✅ Restore cover
        if ($artikel->cover && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->makeDirectory("artikel/{$artikel->id}/cover", 0755, true);
            Storage::disk('public')->move(
                "trash/artikel/{$artikel->id}/cover/{$artikel->cover}",
                "artikel/{$artikel->id}/cover/{$artikel->cover}"
            );
        }

        // ✅ Restore gambar dari isi artikel
        if (! empty($artikel->isi)) {
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

        $artikel->deleted_until = null;
        $artikel->restore();

        // ✅ HAPUS FOLDER TRASH SETELAH SEMUA FILE DIPINDAH
        $trashFolder = "trash/artikel/{$artikel->id}";
        if (Storage::disk('public')->exists($trashFolder)) {
            Storage::disk('public')->deleteDirectory($trashFolder);
        }

        return back()->with('success', 'Artikel berhasil direstore');
    }

    public function forceDelete($id)
    {
        $artikel = Artikel::withTrashed()->findOrFail($id);

        // ✅ Hapus file PDF/artikel permanen
        if ($artikel->file && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/files/{$artikel->file}")) {
            Storage::disk('public')->delete("trash/artikel/{$artikel->id}/files/{$artikel->file}");
        }

        // ✅ Hapus cover permanen
        if ($artikel->cover && Storage::disk('public')->exists("trash/artikel/{$artikel->id}/cover/{$artikel->cover}")) {
            Storage::disk('public')->delete("trash/artikel/{$artikel->id}/cover/{$artikel->cover}");
        }

        // ✅ Hapus gambar dari isi artikel permanen
        if (! empty($artikel->isi)) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $artikel->isi, $matches);
            foreach ($matches[1] as $imgUrl) {
                $path = str_replace('/storage/', '', parse_url($imgUrl, PHP_URL_PATH));
                $trashPath = 'trash/'.$path;

                // Hapus permanen dari trash
                if (Storage::disk('public')->exists($trashPath)) {
                    Storage::disk('public')->delete($trashPath);
                }
            }
        }

        // ✅ Hapus folder artikel di trash jika kosong
        $artikelTrashPath = "trash/artikel/{$artikel->id}";
        if (Storage::disk('public')->exists($artikelTrashPath)) {
            // Cek apakah folder masih ada file
            $files = Storage::disk('public')->allFiles($artikelTrashPath);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($artikelTrashPath);
            }
        }

        $artikel->forceDelete();

        return back()->with('success', 'Artikel dihapus permanen');
    }
}
