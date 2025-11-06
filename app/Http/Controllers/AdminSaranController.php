<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminSaranController extends Controller
{
    public function index()
{
    // Tandai bahwa admin sudah lihat saran hari ini
    session(['saran_terbaru_dilihat' => now()->toDateString()]);

    $saran = Saran::with('user')->orderBy('created_at', 'desc')->paginate(10);
    $totalSaran = Saran::count();
    $hariIni = Carbon::today();
    $terbaru = Saran::whereDate('created_at', $hariIni)->count();

    return view('admin.saran.saran', [
        'saran' => $saran,
        'totalSaran' => $totalSaran,
        'terbaru' => $terbaru,
    ]);
}

    public function destroy(string $id)
    {
        $saran = Saran::findOrfail($id);
        $saran->delete();

        return redirect()->back()->with('success', 'Pesan Berhasil Di Hapus');
    }

    public function getBadge()
    {
        $count = Saran::whereNull('read_at')->count(); // atau Saran::count() kalau mau total
        return response()->json(['count' => $count]);
    }
}
