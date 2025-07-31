<?php

<<<<<<< Updated upstream
use Illuminate\Support\Facades\Route;

=======
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;





>>>>>>> Stashed changes
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

Route::get('/', function () {
    return view('index');
});

<<<<<<< Updated upstream
Route::get('/login', function () {
    return view('login');
})->name('login');
=======
Route::get('/repository', [RepositoryController::class, 'index'])->name('repository');

Route::get('/article', [ArticleController::class, 'article'])->name('article');
Route::get('/article/create', [ArticleController::class, 'create_article'])->name('article.create');
Route::get('/article/{id}', [ArticleController::class, 'article_detail'])->name('article.detail');
Route::get('/profile', function() {
    return view('profile');
})->name('profile');

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
>>>>>>> Stashed changes

Route::post('/login', function () {
    // Logika autentikasi akan ditambahkan nanti
    return redirect('/');
});
