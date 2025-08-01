<?php
use App\Http\Controllers\ArticleController;

use App\Http\Controllers\FileRepoController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\SearchController;
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

    Route::get('/article/create', [ArticleController::class, 'create_article'])->middleware('role:user')->name('article.create');
    Route::post('/article/add', [ArticleController::class, 'add_artikel'])->middleware('role:user')->name('add.artikel');
    Route::get('/article', [ArticleController::class, 'article'])->name('article');
    Route::get('/article/{id}', [ArticleController::class, 'article_detail'])->name('article.detail');

    //Route buat repo
    Route::get('/repository', [RepositoryController::class, 'index'])->name('repository');
    Route::get('/repositori/create', [RepositoryController::class, 'create_repo'])->middleware('role:user')->name('repo.create');
    Route::post('/repositori/add', [RepositoryController::class,  'add_repo'])->middleware('role:user')->name('add.repo');
    Route::get('/repositori/detail/{id}', [RepositoryController::class, 'repo_detail'])->name('repo.detail');
    //aksi
    Route::get('/file/pdf/{id}', [FileRepoController::class, 'showPdf'])->name('file.pdf');
    Route::get('/file/{id}', [FileRepoController::class, 'showFile'])->name('file.show');


    //search
    Route::get('/search', [SearchController::class, 'index'])->name('search.all');

    Route::get('/profile/{id}', [ProfileController::class, 'profile'])->name('profile');
