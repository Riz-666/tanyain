<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\KomentarVote;
use Illuminate\Http\Request;

class AdminKomentarVoteController extends Controller
{
    public function toggle(Request $request, $komentarId)
    {
        $komentar = Komentar::findOrFail($komentarId);

        $vote = KomentarVote::where('komentar_id', $komentarId)
            ->where('user_id', auth()->id())
            ->first();

        if ($vote) {
            $vote->delete(); // hapus vote jika udah ada
        } else {
            KomentarVote::create([
                'komentar_id' => $komentar->id,
                'user_id' => auth()->id(),
                'vote_type' => 'like',
            ]);

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

        return back();
    }
}
