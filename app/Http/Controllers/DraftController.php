<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Draft;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DraftController extends Controller
{
    public function save(Request $request)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'artikel_id' => 'nullable|integer',
            'judul'      => 'nullable|string|max:255',
            'tags'       => 'nullable',
            'content'    => 'nullable|string',
            'coverImage' => 'nullable|string',
            'repository' => 'nullable|string',
        ]);

        Draft::updateOrCreate(
            [
                'user_id'    => $userId,
                'artikel_id' => $data['artikel_id'],
            ],
            [
                'judul'       => $data['judul'] ?? null,
                'tags'        => is_array($data['tags']) ? $data['tags'] : [],
                'content'     => $data['content'] ?? null,
                'cover_image' => $data['coverImage'] ?? null,
                'repository'  => $data['repository'] ?? null,
                'files'       => [],
                'expires_at'  => now()->addDay(),
            ]
        );

        return response()->json(['success' => true]);
    }

    // ⬇️ BARU: Ambil semua draft user
    public function list()
    {
        $drafts = Draft::where('user_id', Auth::id())
            ->where('expires_at', '>', now())
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'drafts'  => $drafts,
        ]);
    }

    // ⬇️ BARU: Load draft berdasarkan ID draft (bukan artikel_id)
    public function loadById($id)
    {
        $draft = Draft::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('expires_at', '>', now())
            ->first();

        if ($draft) {
            $draft->expires_at = now()->addHours(24);
            $draft->save();
        }

        return response()->json([
            'success' => true,
            'draft'   => $draft,
        ]);
    }

    // Tetap pertahankan method lama untuk kompatibilitas (opsional)
    public function load($artikelId = null)
    {
        $draft = Draft::where('user_id', Auth::id())
            ->where('artikel_id', $artikelId)
            ->where('expires_at', '>', now())
            ->first();

        if ($draft) {
            $draft->expires_at = now()->addHours(24);
            $draft->save();
        }

        return response()->json([
            'success' => true,
            'draft'   => $draft,
        ]);
    }
}
