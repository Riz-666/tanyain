<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', [IndexController::class, 'index'])->name('dashboard.user');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

//login & Auth Login
Route::get('/login',[LoginController::class, 'login'])->name('login');
Route::post('/login/auth', [LoginController::class, 'auth'])->name('auth');

//REDIRECT
    Route::get('/redirect', function () {
        return view('redirect');
    });

Route::post('/login', function () {
    // Logika autentikasi akan ditambahkan nanti
    return redirect('/');
});

