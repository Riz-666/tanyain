<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function auth(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required|min:6',
            ],
            [
                'email.required' => 'Email / Username Wajib Di Isi',
                'password.required' => 'Password Wajib Di Isi',
            ],
        );

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Username / Email Tidak Di Temukan'])
                ->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password Tidak Sesuai'])
                ->withInput();
        }

        Auth::login($user);
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'user') {
            return redirect()->route('dashboard.user');
        } else {
            return redirect()->route('login')->withErrors('Data / Akun Tidak Sesuai')->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
