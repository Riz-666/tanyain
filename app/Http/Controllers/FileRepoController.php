<?php

namespace App\Http\Controllers;

use App\Models\FileRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\File;

class FileRepoController extends Controller
{
    public function showPdf($id)
    {
        $file = FileRepo::findOrFail($id);

        // Normalisasi path
        $path = str_replace('\\', '/', $file->path); // pastikan pake slash /
        $path = preg_replace('#/+#', '/', $path); // hilangkan slash ganda
        $path = str_replace('public/', '', $path); // hapus prefix "public/" kalau ada

        // Pastikan folder tambahan_file dipisah
        if (!str_contains($path, 'tambahan_file/')) {
            $path = str_replace('tambahan_file', 'tambahan_file/', $path);
        }

        $fullPath = public_path('storage/' . $path);

        if (!file_exists($fullPath)) {
            dd('File not found', $fullPath); // debug dulu kalau masih error
        }

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->nama_file . '"',
        ]);
    }

    public function showFile($id)
{
    $file = FileRepo::findOrFail($id);

    // Normalisasi path
    $path = str_replace('\\', '/', $file->path);
    $path = preg_replace('#/+#', '/', $path);
    $path = str_replace('public/', '', $path);
    if (!str_contains($path, 'tambahan_file/')) {
        $path = str_replace('tambahan_file', 'tambahan_file/', $path);
    }

    $fullPath = public_path('storage/' . $path);

    if (!file_exists($fullPath)) {
        dd('File not found', $fullPath);
    }

    // Ambil ekstensi file (tanpa titik)
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

    if ($extension === 'pdf') {
        // Tampilkan PDF di browser
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->nama_file . '"',
        ]);
    } else {
        // Paksa download file lainnya (zip, docx, dll)
        return response()->download($fullPath, $file->nama_file);
    }
}
}
