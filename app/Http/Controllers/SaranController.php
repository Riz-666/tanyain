<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use Illuminate\Http\Request;

class SaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.saran');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $ip = $request->ip();

        // Validasi input
        $request->validate([
            'pesan' => 'required|max:100',
            'nama' => $userId ? 'nullable' : 'required|string|max:50',
        ]);

        // Cek saran terakhir (1x per hari)
        $lastSaran = Saran::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('ip_address', $ip))
            ->latest()
            ->first();

        if ($lastSaran && now()->diffInHours($lastSaran->created_at) < 24) {
            return back()->with('saranError', 'Kamu cuma bisa kirim 1 saran per hari.');
        }

        // Simpan data saran
        Saran::create([
            'user_id' => $userId,
            'nama' => $userId ? auth()->user()->nama : $request->nama,
            'ip_address' => $ip,
            'pesan' => $request->pesan,
        ]);

        return back()->with('success', 'Terima kasih! Saran kamu sudah terkirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
