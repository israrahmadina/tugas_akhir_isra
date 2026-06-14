<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kelompok\ProdukController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\Kelompok\NotifikasiController;
use App\Http\Controllers\Penyuluh\PenyuluhDashboardController;
use App\Http\Controllers\Penyuluh\VerifikasiController;
use App\Http\Controllers\Penyuluh\RiwayatController;
use App\Models\Produk;
use App\Models\KelompokUsaha;
use App\Models\Laporan;
use App\Models\Role;
use App\Models\User;


// REDIRECT ROOT 
Route::get('/', function () {
    return redirect('/login');
});

// GUEST (BELUM LOGIN)
Route::middleware('guest')->group(function () {

// LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// REGISTER
Route::get('/register', function () {
     return view('register');
 })->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// API WILAYAH
Route::get('/wilayah/provinsi', [App\Http\Controllers\WilayahController::class, 'provinsi']);
Route::get('/wilayah/kabupaten/{kode}', [App\Http\Controllers\WilayahController::class, 'kabupaten']);
Route::get('/wilayah/kecamatan/{kode}', [App\Http\Controllers\WilayahController::class, 'kecamatan']);
Route::get('/wilayah/desa/{kode}', [App\Http\Controllers\WilayahController::class, 'desa']);


// AUTH (SUDAH LOGIN)
Route::middleware('auth')->group(function () {

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD BY ROLE
Route::get('/dashboard', function () {
    $role = auth()->user()->role->role_name;
    if ($role === 'Admin') {
        $userCount = User::count();
        $penyuluhCount = User::whereHas('role', fn($query) => $query->where('role_name', 'Penyuluh'))->count();
        $kelompokCount = \App\Models\KelompokUsaha::count();
        $komoditasCount = \App\Models\Produk::count();
        $laporanCount = Laporan::count();

        return view('dashboard', compact(
            'userCount',
            'penyuluhCount',
            'kelompokCount',
            'komoditasCount',
            'laporanCount'
        ));
    }
    if ($role === 'KPHL') {
        return view('kphl.dashboard_kphl');
    }
    if ($role === 'Penyuluh') {
        return redirect()->route('penyuluh.dashboard');
    }
    if ($role === 'Kelompok Usaha') {
        return app(App\Http\Controllers\Kelompok\DashboardController::class)->index();
    }
    // fallback
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', [App\Http\Controllers\AuthController::class, 'editProfile'])
    ->name('profile.edit');
Route::put('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])
    ->name('profile.update');



    
 //PRODUK
Route::get('/kelompok/produk', [ProdukController::class, 'index'])
    ->name('kelompok.produk');
Route::get('/kelompok/produk/create', [ProdukController::class, 'create'])
    ->name('kelompok.produk.create'); 
Route::post('/produk', [ProdukController::class, 'store'])
    ->name('kelompok.produk.store');
Route::get('/kelompok/produk/{produk}/edit', [ProdukController::class, 'edit'])
    ->name('kelompok.produk.edit');
Route::put('/kelompok/produk/{produk}', [ProdukController::class, 'update'])
    ->name('kelompok.produk.update');
Route::delete('/kelompok/produk/{produk}', [ProdukController::class, 'destroy'])
    ->name('kelompok.produk.destroy');

// NOTIFIKASI KELOMPOK
Route::get('/kelompok/notifikasi', [NotifikasiController::class, 'index'])
    ->name('kelompok.notifikasi');
Route::post('/kelompok/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])
    ->name('kelompok.notifikasi.mark-read');
Route::post('/kelompok/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])
    ->name('kelompok.notifikasi.mark-all-read');


// NOTIFIKASI
Route::post('/notifikasi/{id}/read', function ($id) {
    $notif = \App\Models\Notifikasi::where('notifikasi_id', $id)
        ->where('user_id', auth()->user()->user_id)
        ->firstOrFail();
    $notif->update(['is_read' => true]);
    return response()->json(['success' => true]);
})->name('notifikasi.read');

Route::post('/notifikasi/read-all', function () {
    \App\Models\Notifikasi::where('user_id', auth()->user()->user_id)
        ->where('is_read', false)
        ->update(['is_read' => true]);
    return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
})->name('notifikasi.read-all');

//  PELAPORAN
Route::get('/pelaporan', [App\Http\Controllers\PelaporanController::class, 'index'])
    ->name('pelaporan.index');
Route::post('/pelaporan', [App\Http\Controllers\PelaporanController::class, 'store'])
    ->name('pelaporan.store');
Route::put('/pelaporan/{id}', [App\Http\Controllers\PelaporanController::class, 'update'])
    ->name('pelaporan.update');
Route::get('/pelaporan/get-atribut', [App\Http\Controllers\PelaporanController::class, 'create'])
    ->name('pelaporan.get-atribut');

// ADMIN AREA
 Route::middleware('role:Admin')->prefix('admin')->group(function () {
        Route::resource('kphl', App\Http\Controllers\Admin\KphlController::class);
        Route::resource('penyuluh', App\Http\Controllers\Admin\PenyuluhController::class);
        Route::resource('skema', App\Http\Controllers\Admin\SkemaController::class);
        Route::resource('kelompok', App\Http\Controllers\Admin\KelompokUsahaController::class);
        Route::resource('atribut', App\Http\Controllers\Admin\AtributPelaporanController::class);
        Route::resource('komoditas', App\Http\Controllers\Admin\KomoditasController::class);
    });

// PENYULUH 
Route::prefix('penyuluh')
    ->middleware(['auth'])
    ->group(function () {

    Route::get('/dashboard', [PenyuluhDashboardController::class, 'index'])
        ->name('penyuluh.dashboard');
    Route::get('/verifikasi', [VerifikasiController::class, 'index'])
        ->name('penyuluh.verifikasi');
    Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])
        ->name('penyuluh.verify');
    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('penyuluh.riwayat');
    });

// KPHL AREA
Route::middleware('role:KPHL')->prefix('kphl')->group(function () {
    Route::resource('jadwal', App\Http\Controllers\Kphl\JadwalController::class)->names('kphl.jadwal');
    Route::get('/validasi', [App\Http\Controllers\Kphl\ValidasiController::class, 'index'])->name('kphl.validasi');
    Route::post('/validasi/{id}', [App\Http\Controllers\Kphl\ValidasiController::class, 'validateReport'])->name('kphl.validate');
});

});