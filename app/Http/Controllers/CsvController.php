<?php

namespace App\Http\Controllers;

use App\Models\FileRepo;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    /**
     * Tampilkan isi CSV untuk DataTable
     */
    public function show(FileRepo $fileRepo)
    {
        // Pastikan file CSV
        if ($fileRepo->ekstensi !== 'csv') {
            return response()->json(['error' => 'Bukan file CSV'], 400);
        }

        // Ambil full path file
        $path = public_path('storage/' . str_replace('public/', '', ltrim($fileRepo->path, '/')));

        if (!file_exists($path)) {
            \Log::error('File CSV tidak ditemukan', [
                'requested_path' => $path,
                'db_path' => $fileRepo->path,
            ]);
            return response()->json(['error' => 'File CSV tidak ditemukan'], 404);
        }

        try {
            $headers = [];
            $records = [];

            if (($handle = fopen($path, 'r')) !== false) {
                // Baca header CSV
                $headers = fgetcsv($handle);
                if (!$headers) {
                    fclose($handle);
                    return response()->json(['error' => 'Tidak bisa membaca header CSV'], 400);
                }

                // Baca semua baris
                while (($data = fgetcsv($handle)) !== false) {
                    $records[] = $data;
                }

                fclose($handle);
            }

            // Return JSON untuk JS DataTable
            return response()->json([
                'headers' => $headers,
                'records' => $records,
            ]);

        } catch (\Exception $e) {
            \Log::error('Gagal membaca CSV: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membaca CSV: ' . $e->getMessage()], 500);
        }
    }
}
