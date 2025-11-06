<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function auth(Request $request)
    {
        // Validasi input
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

        // Cari user berdasarkan email atau username
        $user = User::where('role', 'user')
            ->whereNull('deleted_at')
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email)->orWhere('username', $request->email);
            })
            ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()
                ->withErrors(['email' => 'Username / Email Tidak Ditemukan'])
                ->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password Tidak Benar'])
                ->withInput();
        }

        // Handle "Remember Me" — ambil nilai checkbox
        $remember = $request->has('remember');

        // Login user
        Auth::login($user, $remember);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()
            ->route('dashboard.user')
            ->with('login', 'Selamat Datang ' . Auth::user()->nama);
    }

    public function logout()
    {
        $role = Auth::check() ? Auth::user()->role : null;

        // Logout
        Auth::logout();

        // Bersihkan session & token
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Redirect sesuai role
        if ($role === 'super_admin') {
            return redirect()->route('admin.login')->with('login', 'Berhasil Logout');
        } else {
            return redirect()->route('index')->with('login', 'Berhasil Logout');
        }
    }

    public function adminLogin()
    {
        return view('admin.login');
    }

    public function authAdmin(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'email' => 'required|string',
                'password' => 'required|min:6',
                'g-recaptcha-response' => 'required',
            ],
            [
                'email.required' => 'Email / Username wajib diisi',
                'email.email' => 'Format email salah',
                'password.required' => 'Password wajib diisi',
                'g-recaptcha-response.required' => 'Captcha wajib diisi',
            ],
        );

        // VALIDASI RECAPTCHA
        $recaptcha = $request->input('g-recaptcha-response');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $recaptcha,
            'remoteip' => $request->ip(),
        ]);
        $body = $response->json();
        if (!isset($body['success']) || !$body['success']) {
            return back()
                ->withErrors(['login' => 'Captcha tidak valid'])
                ->withInput();
        }

        $loginKey = 'login_attempts_' . $request->ip();
        $throttleKey = $loginKey;
        $maxAttempts = 5;
        $lockoutTime = 60 * 5;

        // Cek lockout
        if (Cache::has($throttleKey . '_lock')) {
            $seconds = Cache::get($throttleKey . '_lock') - time();
            return back()
                ->withErrors([
                    'login' => "Terlalu banyak percobaan login. Coba lagi dalam $seconds detik.",
                ])
                ->with('lockout_seconds', $seconds)
                ->withInput();
        }

        // Cari user
        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();

        // Cek password
        if (!$user || !Hash::check($request->password, $user->password)) {
            $attempts = Cache::get($throttleKey, 0) + 1;
            Cache::put($throttleKey, $attempts, $lockoutTime);

            if ($attempts >= $maxAttempts) {
                Cache::put($throttleKey . '_lock', time() + $lockoutTime, $lockoutTime);
                return back()
                    ->withErrors([
                        'login' => "Terlalu banyak percobaan login. Kamu diblokir $lockoutTime detik.",
                    ])
                    ->withInput();
            }

            return back()
                ->withErrors([
                    'login' => "Email atau password salah. Percobaan ke-$attempts dari $maxAttempts.",
                ])
                ->withInput();
        }

        // Cek role
        if ($user->role !== 'super_admin') {
            return back()
                ->withErrors([
                    'login' => 'Akun tidak memiliki akses admin.',
                ])
                ->withInput();
        }

        // Login sukses → reset counter + regenerate session
        Cache::forget($throttleKey);
        Cache::forget($throttleKey . '_lock');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard.admin')
            ->with('login', 'Selamat datang ' . $user->nama);
    }
}
