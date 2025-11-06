<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Auth::user()->notifikasi()
            ->orderBy('created_at', 'desc')
            ->with([
                'artikel',
                'komentar.user',
                'komentar.tags.taggedUser',
                'pengirim'
            ])
            ->paginate(10);

        return view('user.notifikasi', compact('notifikasi'));
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $notif = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$notif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan atau bukan milik Anda.',
                ], 404);
            }

            $notif->update(['status' => 'dibaca']);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $updated = Auth::user()
                ->notifikasi()
                ->where('status', 'belum_dibaca')
                ->update(['status' => 'dibaca']);

            return response()->json([
                'success' => true,
                'updated_count' => $updated,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function countUnread()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak login'], 401);
        }

        $count = $user->notifikasi()->where('status', 'belum_dibaca')->count();

        return response()->json(['count' => $count]);
    }

    public function hapusSemua(Request $request)
    {

        Notifikasi::where('user_id', auth()->id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dihapus.'
        ]);
    }
}
