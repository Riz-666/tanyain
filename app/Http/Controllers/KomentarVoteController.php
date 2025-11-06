<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\KomentarVote;
use Illuminate\Http\Request;

class KomentarVoteController extends Controller
{
    public function toggle(Request $request, $komentarId)
    {
        $komentar = Komentar::findOrFail($komentarId);

        $vote = KomentarVote::where('komentar_id', $komentarId)
            ->where('user_id', auth()->id())
            ->first();

        if ($vote) {
            $vote->delete(); // hapus vote jika udah ada
            $action = 'unliked';
        } else {
            KomentarVote::create([
                'komentar_id' => $komentar->id,
                'user_id' => auth()->id(),
                'vote_type' => 'like',
            ]);
            $action = 'liked';

            // Kirim notifikasi like ke pemilik komentar (jika bukan diri sendiri)
            if ($komentar->user_id != auth()->id()) {
                \App\Models\Notifikasi::create([
                    'user_id' => $komentar->user_id,
                    'tipe' => 'like',
                    'referensi_id' => $komentar->id,
                    'pesan' => auth()->user()->nama . ' menyukai komentar Anda.',
                    'status' => 'belum_dibaca',
                ]);
            }
        }

        // Get updated vote count
        $voteCount = KomentarVote::where('komentar_id', $komentarId)
            ->where('vote_type', 'like')
            ->count();

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'vote_count' => $voteCount,
                'message' => $action === 'liked' ? 'Komentar disukai!' : 'Like dihapus!'
            ]);
        }

        return back();
    }
}
