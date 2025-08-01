<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RepositoryController;

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

    Route::get('/', [IndexController::class, 'index'])->name('index');
    //login & Auth Login
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login/auth', [LoginController::class, 'auth'])->name('auth');



    //REDIRECT
    Route::get('/redirect', function () {
        return view('redirect');
    });

    Route::get('/dashboard/user', [IndexController::class, 'index'])->name('dashboard.user');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //Route buat artikel
    Route::get('/article/create', [ArticleController::class, 'create_article'])->name('article.create');
    Route::post('/article/add', [ArticleController::class, 'add_artikel'])->name('add.artikel');

    Route::get('/article', [ArticleController::class, 'article'])->name('article');
    Route::get('/article/{id}', [ArticleController::class, 'article_detail'])->name('article.detail');

    Route::get('/repository', [RepositoryController::class, 'index'])->name('repository');


    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
