<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(){
        if(Auth::check()){
            $artikel = Artikel::latest()->get();
        }else{
            $artikel = Artikel::with('repositori', 'user')->where('status', 'publik')->latest()->get();
        }
        return view('index',[
            'artikel' => $artikel
        ]);
    }
}
