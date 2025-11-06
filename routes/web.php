<?php
use App\Http\Controllers\AdminAktivitasController;

use App\Http\Controllers\AdminArtikelController;
use App\Http\Controllers\AdminChartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKomentarController;
use App\Http\Controllers\AdminKomentarVoteController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminRepoController;
use App\Http\Controllers\AdminSaranController;
use App\Http\Controllers\AdminTagController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\FileRepoController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\KomentarVoteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\SaranController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrashController;
use Illuminate\Http\Request;
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

    Route::get('/', [IndexController::class, 'index'])->middleware('guest.admin')->name('index');

    //login & Auth Login
    Route::get('/administrator/login', [LoginController::class, 'adminLogin'])->middleware('guest.admin')->name('admin.login');
    Route::post('/administrator/auth', [LoginController::class, 'authAdmin'])->middleware('guest.admin')->name('admin.auth');
    Route::get('/login', [LoginController::class, 'login'])->middleware('guest.admin')->name('login');
    Route::post('/login/auth', [LoginController::class, 'auth'])->name('auth');


    //dashboard
    Route::get('/dashboard/user', [IndexController::class, 'index'])->middleware('role:user')->name('dashboard.user');
    //LOgout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('role:user,super_admin')->name('logout');
    //trash
    Route::get('/trash', [TrashController::class, 'trash'])->middleware('role:user')->name('artikel.trash');

    //Route buat artikel
    Route::get('/article/create', [ArticleController::class, 'create_article'])->middleware('role:user')->name('article.create');
    Route::post('/article/add', [ArticleController::class, 'add_artikel'])->middleware('role:user')->name('add.artikel');
    Route::get('/article', [ArticleController::class, 'article'])->middleware('guest.admin')->name('article');
    Route::get('/article/{id}', [ArticleController::class, 'article_detail'])->middleware('guest.admin')->name('article.detail');
    // quill artikel fotp
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->middleware('role:user')->name('upload.image');
    Route::post('/delete-image', [ImageUploadController::class, 'deleteImage'])->middleware('role:user')->name('artikel.deleteImage');
    //edit artikel
    Route::get('/artikel/edit/{id}', [ArticleController::class, 'edit'])->middleware('role:user')->name('edit.artikel');
    //file artikel
    Route::post('/artikel/upload-file-temp', [ArtikelController::class, 'uploadFileTemp']);
    Route::post('/artikel/delete-file-temp', [ArtikelController::class, 'deleteFileTemp']);
    Route::post('/artikel/update/{id}', [ArticleController::class, 'update'])->middleware('role:user')->name('update.artikel');
    //softDelete
    Route::post('/artikel/hapus/{id}', [ArticleController::class, 'destroy'])->middleware('role:user')->name('artikel.destroy');
    //Restore artikel
    Route::post('/artikel/restore/{id}', [ArticleController::class, 'restore'])->middleware('role:user')->name('artikel.restore');
    //forceDelete
    Route::post('/artikel/force-delete/{id}', [ArticleController::class, 'forceDelete'])->middleware('role:user')->name('artikel.forceDelete');
    //draft
    Route::post('/drafts/save', [DraftController::class, 'save'])->middleware('role:user')->name('draft.save');
    Route::get('/drafts/load/{artikelId?}', [DraftController::class, 'load'])->middleware('role:user')->name('draft.load');
    Route::get('/drafts/list', [DraftController::class, 'list'])->middleware('role:user')->name('drafts.list');

    Route::post('/save', [DraftController::class, 'save'])->name('drafts.save');
    Route::get('/load/{artikelId?}', [DraftController::class, 'load'])->name('drafts.load');
    Route::get('/list', [DraftController::class, 'list'])->name('drafts.list');
    Route::get('/load-by-id/{id}', [DraftController::class, 'loadById'])->name('drafts.loadById');


    //Route buat repo
    Route::get('/repository', [RepositoryController::class, 'index'])->name('repository');
    Route::get('/repositori/create', [RepositoryController::class, 'create_repo'])->middleware('role:user')->name('repo.create');
    Route::post('/repositori/add', [RepositoryController::class,  'add_repo'])->middleware('role:user')->name('add.repo');
    Route::get('/repositori/detail/{id}', [RepositoryController::class, 'repo_detail'])->middleware('guest.admin')->name('repo.detail');
    //aksi
    Route::get('/file/pdf/{id}', [FileRepoController::class, 'showPdf'])->middleware('guest.admin')->name('file.pdf');
    Route::get('/file/{id}', [FileRepoController::class, 'showFile'])->middleware('guest.admin')->name('file.show');
    //edit
    Route::get('/repo/{id}/edit', [RepositoryController::class, 'edit'])->middleware('role:user')->name('edit.repo');
    Route::post('/repo/update/{id}', [RepositoryController::class, 'update'])->middleware('role:user')->name('update.repo');
    // Hapus repo (soft delete)
    Route::post('/hapus-repo/{id}', [RepositoryController::class, 'destroy'])->middleware('role:user')->name('repo.destroy');
    // Restore repo
    Route::post('/restore-repo/{id}', [RepositoryController::class, 'restore'])->middleware('role:user')->name('repo.restore');
    // Force delete repositori manual
    Route::post('/force-delete-repo/{id}', [RepositoryController::class, 'forceDelete'])->middleware('role:user')->name('repo.forceDelete');
    // hapus file 1/1
    Route::delete('/filerepo/{id}', [FileRepoController::class, 'destroy'])->middleware('role:user')->name('fileRepo.destroy');
    // CSV Routes
    Route::get('/csv/{fileRepo}', [CsvController::class, 'show'])->name('csv.show');



    Route::get('/file', [FileRepoController::class, 'index'])->name('file');

    // Bulk Hapus Permanen (Artikel + Repositori)
    Route::post('/trash/bulk-delete', [TrashController::class, 'bulkDelete'])->name('trash.bulk.delete')->middleware('role:user');
    Route::post('/trash/bulk-restore', [TrashController::class, 'bulkRestore'])->name('trash.bulk.restore')->middleware('role:user');

    //search
    Route::get('/search', [SearchController::class, 'index'])->name('search.all');
    Route::get('/search/tag/{id}', [SearchController::class, 'index'])->name('search.tag');

    //profile
    Route::get('/profile/{id}', [ProfileController::class, 'profile'])->middleware('guest.admin')->name('profile');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'edit_profile'])->middleware('role:user')->name('profile.edit');
    Route::post('/profile/update/{id}', [ProfileController::class, 'update_profile'])->middleware('role:user')->name('profile.update');
    // Hapus foto profile (ajax)
    Route::delete('/profile/photo/{id}', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy')->middleware('auth');

    //Saran
    Route::get('/saran', [SaranController::class, 'index'])->middleware('guest.admin')->name('saran');
    Route::post('/saran/post', [SaranController::class, 'store'])->middleware('guest.admin')->name('saran.store');

    //Komentar
    Route::post('/artikel/{artikel}/komentar', [KomentarController::class, 'store'])->middleware('role:user')->name('komentar.store');
    Route::delete('/komentar/{komentar}', [KomentarController::class, 'destroy'])->middleware('role:user')->name('komentar.destroy');
    Route::post('/komentar/{komentar}/reply', [KomentarController::class, 'reply'])->middleware('role:user')->name('komentar.reply');
    Route::post('/komentar/{id}/vote', [KomentarVoteController::class, 'toggle'])->middleware('role:user')->name('komentar.vote');
    Route::get('/article/missing', function (Request $request) {
            // Hapus notifikasi jika ada
            if ($request->has('notif_id')) {
                \App\Models\Notifikasi::where('id', $request->notif_id)->delete();
            }
            return redirect()->back()->with('swal', [
                'icon' => 'warning',
                'title' => 'Oops!',
                'text' => 'Konten yang kamu cari sudah dihapus.',
                'timer' => 3000,
                'showConfirmButton' => false
            ]);
        })->name('article.detail.missing');

    // NOTIFIKASI
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->middleware('auth')->name('notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->middleware('auth')->name('notifikasi.baca-semua');
    Route::get('/notifikasi/jumlah', [NotifikasiController::class, 'countUnread'])->middleware('auth')->name('notifikasi.jumlah');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->middleware('role:user')->name('notifikasi.index');
    Route::delete('/notifikasi/hapus-semua', [NotifikasiController::class, 'hapusSemua'])->middleware('auth')->name('notifikasi.hapus-semua');

    Route::get('/ketentuan', function () {
        return view('user.ketentuan_pengguna');
    })->name('ketentuan');

    Route::get('/privasi', function () {
        return view('user.kebijakan_privasi');
    })->name('privasi');

    Route::get('/bantuan', function () {
        return view('user.bantuan');
    })->name('bantuan');



Route::prefix('admin')->middleware(['role:super_admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    //AKTIVITAS TERBARU
    Route::get('/Aktivitas-Terbaru',[AdminAktivitasController::class, 'aktivitas'])->name('admin.aktivitas');
    //detail Artikel
    Route::get('/detail-Artikel/{id}',[AdminArtikelController::class, 'detail'])->name('admin.artikel.detail');
    //detail repo
    Route::get('/detail-repositori/{id}',[AdminRepoController::class, 'detail'])->name('admin.repo.detail');

    //User
    Route::get('/management/user', [AdminUserController::class, 'user'])->name('admin.user');
    Route::get('/management/user/create', [AdminUserController::class, 'create'])->name('admin.user.create');
    Route::post('/management/user/store', [AdminUserController::class, 'store'])->name('admin.user.store');
    Route::get('/management/user/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user.edit');
    Route::post('/management/user/update/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');

    Route::get('/management/trash/user', [AdminUserController::class, 'trashUser'])->name('admin.trash.user');
    //SOFT DELETE USER
    Route::post('/management/user/delete/{user}', [AdminUserController::class, 'destroy'])->name('admin.user.softDelete');
    Route::post('/management/user/restore/{id}', [AdminUserController::class, 'restore'])->name('admin.user.restore');
    Route::post('/management/user/force-delete/{id}', [AdminUserController::class, 'forceDelete'])->name('admin.user.forceDelete');


    //ARTIKEL
    Route::get('/management/trash/artikel', [AdminArtikelController::class, 'trashArtikel'])->name('admin.trash.artikel');
    //SoftDelte
    Route::post('/artikel/destroy/{id}', [AdminArtikelController::class, 'destroy'])->name('admin.artikel.destroy');
    //Restore artikel
    Route::post('/artikel/trash/restore/{id}', [AdminArtikelController::class, 'restore'])->name('artikel.trash.restore');
    //forceDelete
    Route::post('/artikel/trash/force-delete/{id}', [AdminArtikelController::class, 'forceDelete'])->name('artikel.trash.forceDelete');

    //REPO
    Route::get('/management/trash/repository', [AdminRepoController::class, 'trashRepo'])->name('admin.trash.repo');
    //SoftDelte
    Route::post('/repo/destroy/{id}', [AdminRepoController::class, 'destroy'])->name('admin.repo.destroy');
    //FILREPO
    Route::post('/repo/destroy/file/{id}', [AdminRepoController::class, 'destroyFile'])->name('admin.file.destroy');
    Route::get('/repo/view/file/{id}', [AdminRepoController::class, 'showPdf'])->name('admin.file.showPdf');
    Route::get('/repo/download/file/{id}', [AdminRepoController::class, 'showFile'])->name('admin.file.download');
    //Restore artikel
    Route::post('/repo/trash/restore/{id}', [AdminRepoController::class, 'restore'])->name('repo.trash.restore');
    //forceDelete
    Route::post('/repo/trash/force-delete/{id}', [AdminRepoController::class, 'forceDelete'])->name('repo.trash.forceDelete');

    //Kelola Tag
    Route::get('/management/tag', [AdminTagController::class, 'index'])->name('admin.tag');
    Route::get('/management/tag/create', [AdminTagController::class, 'create'])->name('admin.tag.create');
    Route::post('/management/tag/store', [AdminTagController::class, 'store'])->name('admin.tag.store');
    Route::post('/management/tag/delete/{id}', [AdminTagController::class, 'destroy'])->name('admin.tag.destroy');

    //download Template
    Route::get('/admin/tag/download-template', [AdminTagController::class, 'downloadTemplate'])->name('admin.tag.downloadTemplate');
    //import
    Route::post('/admin/tag/import', [AdminTagController::class, 'import'])->name('admin.tag.import');

    //saran
    Route::get('/admin/saran', [AdminSaranController::class, 'index'])->name('admin.saran');
    Route::post('/admin/saran/destroy/{id}', [AdminSaranController::class, 'destroy'])->name('admin.saran.destroy');
    Route::get('/admin/saran/badge', [AdminSaranController::class, 'getBadge'])->name('admin.saran.badge');

    //profile
    Route::get('/admin/profile/{id}', [AdminProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update/{id}', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');

    //Komentar
    Route::post('/admin/artikel/{artikel}/komentar', [AdminKomentarController::class, 'store'])->name('admin.komentar.store');
    Route::post('/admin/komentar/{komentar}/delete', [AdminKomentarController::class, 'destroy'])->name('admin.komentar.destroy');
    Route::post('/admin/komentar/{komentar}/reply', [AdminKomentarController::class, 'reply'])->name('admin.komentar.reply');
    Route::post('/admin/komentar/{id}/vote', [AdminKomentarVoteController::class, 'toggle'])->name('admin.komentar.vote');

    //Notifikasi
    Route::get('/admin/notifikasi', [AdminNotifikasiController::class, 'index'])->middleware('role:admin')->name('admin.notifikasi');
    Route::post('/admin/notifikasi/{id}/baca', [AdminNotifikasiController::class, 'markAsRead'])->middleware('role:admin')->name('admin.notifikasi.baca');
    Route::post('/admin/notifikasi/baca-semua', [AdminNotifikasiController::class, 'markAllAsRead'])->middleware('role:admin')->name('admin.notifikasi.baca-semua');
    Route::get('/admin/notifikasi/jumlah', [AdminNotifikasiController::class, 'countUnread'])->middleware('role:admin')->name('admin.notifikasi.jumlah');
    Route::get('/admin/artikel/{artikel}', [AdminArtikelController::class, 'show'])->name('admin.artikel.show');
});
