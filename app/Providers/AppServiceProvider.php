<?php

namespace App\Providers;

use App\Models\Artikel;
use App\Models\FileRepo;
use App\Models\Repositori;
use App\Models\Saran;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (config('app.env') === 'local') {
        //     URL::forceScheme('https');
        // }
        Carbon::setLocale('id');
        View::composer('*', function ($view) {
            $totalArtikel = Artikel::count();
            $totalRepo = Repositori::count();
            $totalTag = Tag::count();
            $totalFile = FileRepo::count();
            $totalUser = User::where('role', 'user')->count();
            $saranHariIni = Saran::whereDate('created_at', Carbon::today())->count();
            $sudahDilihat = session('saran_terbaru_dilihat') === Carbon::today()->toDateString();
            $latestSaran = Saran::latest()->take(3)->get();

            $view->with([
                'totalArtikel' => $totalArtikel,
                'totalRepo' => $totalRepo,
                'totalTag' => $totalTag,
                'totalPengguna' => $totalUser,
                'totalFile' => $totalFile,
                'saranHariIni' => $saranHariIni,
                'sudahDilihat' => $sudahDilihat,
                'latestSaran' => $latestSaran,
            ]);

            $view->with('getFileIcon', function ($ext) {
                $map = [
                    'zip' => 'fa-file-archive',
                    'rar' => 'fa-file-archive',
                    'tar' => 'fa-file-archive',
                    'csv' => 'fa-file-archive',
                    'sql' => 'fa-file-code',
                    'jpg' => 'fa-file-image',
                    'jpeg' => 'fa-file-image',
                    'png' => 'fa-file-image',
                    'mp4' => 'fa-file-video',
                    'pptx' => 'fa-file-powerpoint',
                    'pdf' => 'fa-file-pdf',
                    'xlsx' => 'fa-file-excel',
                    'doc' => 'fa-file-word',
                    'docx' => 'fa-file-word',
                ];

                return $map[strtolower($ext)] ?? 'fa-file';
            });

            $view->with('formatFileSize', function ($bytes) {
                // Validasi input: pastikan angka dan >= 0
                if (!is_numeric($bytes) || $bytes < 0) {
                    return '0 Bytes';
                }

                $bytes = (int) $bytes;

                if ($bytes === 0) {
                    return '0 Bytes';
                }

                $k = 1024;
                $sizes = ['Bytes', 'KB', 'MB', 'GB'];
                $i = floor(log($bytes, $k));

                // Batasi agar tidak melebihi jumlah ukuran yang tersedia
                if ($i >= count($sizes)) {
                    $i = count($sizes) - 1;
                }

                return number_format($bytes / pow($k, $i), 2, '.', '') . ' ' . $sizes[$i];
            });
        });
    }
}
