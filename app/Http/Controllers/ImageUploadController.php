<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
   
    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower($file->getClientOriginalExtension());

            if (! in_array($ext, $allowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe file tidak diizinkan. Hanya: '.implode(', ', $allowed),
                ], 400);
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran file terlalu besar. Maksimal 5MB.',
                ], 400);
            }

            // ✅ Simpan di folder "temp" — dan biarkan permanen
            $filename = time().'_'.str()->random(6).'.'.$ext;
            $path = $file->storeAs('public/artikel/temp', $filename);
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'temp_path' => str_replace('public/', '', $path),
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function deleteImage(Request $request)
    {
        $url = $request->input('url');

        if ($url) {
            // Parse URL untuk mendapatkan path
            $path = parse_url($url, PHP_URL_PATH);

            // Hapus /storage/ dari awal path
            $relativePath = ltrim(str_replace('/storage/', '', $path), '/');

            // Cek apakah file exists dan hapus
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);

                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus',
                    'path' => $relativePath,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan',
                'path' => $relativePath,
            ], 404);
        }

        return response()->json([
            'success' => false,
            'message' => 'URL tidak valid',
        ], 400);
    }
}
