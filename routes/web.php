<?php

use App\Controllers\Core\AuthController;
use App\Controllers\Core\DashboardController;
use App\Controllers\Core\DatabaseController;
use App\Controllers\Core\DocsController;
use App\Controllers\Core\RoleController;
use App\Controllers\Core\UserController;
use App\Controllers\KategoriController;
use App\Controllers\AlatController;
use App\Controllers\PeminjamanController;
use App\Controllers\PeminjamPortalController;
use App\Controllers\PengembalianController;

use Sakuci\Route;

/*
|--------------------------------------------------------------------------
| Route Web
|--------------------------------------------------------------------------
| Daftarkan seluruh route aplikasi di sini.
|
| Cara menulis action:
|   [HomeController::class, 'index']   -> disarankan
|   'HomeController@index'             -> namespace App\Controllers otomatis
|   function () { ... }                -> closure
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/docs', [DocsController::class, 'index'])->name('docs');

/*
|--------------------------------------------------------------------------
| Login multi-role
|--------------------------------------------------------------------------
| Lihat /docs untuk penjelasan lengkap langkah demi langkah.
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt')->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Route untuk peminjam
Route::get('/peminjaman/katalog', [PeminjamanController::class, 'katalog'])->name('peminjaman.katalog')->middleware('auth');
Route::get('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan')->middleware('auth');
Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'simpan'])->name('peminjaman.simpan')->middleware('auth');

// Rute riwayat peminjaman (agar tidak error 404)
Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat')->middleware('auth');

// Grup admin dengan middleware auth (bebas akses asal sudah login)
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/database/export', [DatabaseController::class, 'export'])->name('admin.database.export');
    Route::get('/kategori/create',[KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori',[KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id_kategori}/edit',[KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id_kategori}',[KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id_kategori}',[KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
    Route::get('/alat/create', [AlatController::class, 'create'])->name('alat.create');
    Route::post('/alat/store', [AlatController::class, 'store'])->name('alat.store');
    Route::get('/alat/{id_alat}/edit', [AlatController::class, 'edit'] )->name('alat.edit');
    Route::put('/alat/{id_alat}', [AlatController::class, 'update'] )->name('alat.update');
    Route::delete('/alat/{id_alat}', [AlatController::class, 'delete'] )->name('alat.delete');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::post('/peminjaman/{id}/update', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id_peminjaman}/delete', [PeminjamanController::class, 'delete'])->name('peminjaman.delete');

    Route::post('/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('/pengembalian/{id}/edit', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::post('/pengembalian/{id}/update', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::post('/pengembalian/{id}/delete', [PengembalianController::class, 'delete'])->name('pengembalian.delete');

});

/*
|--------------------------------------------------------------------------
| Route role dinamis
|--------------------------------------------------------------------------
| Blok di bawah ini dikelola otomatis oleh RoleController saat admin
| menambah, mengganti nama, atau menghapus role lewat /admin/roles.
| Jangan diedit manual -- perubahan bisa tertimpa.
*/
// @generated-roles:start

// @role:peminjam:start
Route::group(['prefix' => 'peminjam', 'middleware' => 'peminjam'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('peminjam.dashboard');
});
// @role:peminjam:end
// @role:petugas:start
Route::group(['prefix' => 'petugas', 'middleware' => 'petugas'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('petugas.dashboard');
});
// @role:petugas:end
// @role:pengguna:start
Route::group(['prefix' => 'pengguna', 'middleware' => 'pengguna'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('pengguna.dashboard');
});
// @role:pengguna:end
// @generated-roles:end