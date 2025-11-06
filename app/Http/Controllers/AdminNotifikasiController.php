<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Auth::user()->notifikasi()
            ->orderBy('created_at', 'desc')
            ->with([
                'komentar.artikel',
                'komentar.user',
                'pengirim'
            ])
            ->paginate(10);

        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead(Request $request, $id)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update(['status' => 'dibaca']);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $updated = Auth::user()
            ->notifikasi()
            ->where('status', 'belum_dibaca')
            ->update(['status' => 'dibaca']);

        return response()->json(['success' => true, 'updated_count' => $updated]);
    }

    public function countUnread()
    {
        $count = Auth::user()
            ->notifikasi()
            ->where('status', 'belum_dibaca')
            ->count();

        return response()->json(['count' => $count]);
    }
}
