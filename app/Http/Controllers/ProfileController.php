<?php

namespace App\Http\Controllers;

use App\Models\Repositori;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(String $id){
        $user = User::with('artikel', 'repositori' ,'viewArtikel')->findOrfail($id);
        return view('profile', [
            'user' => $user
        ]);
    }
}
