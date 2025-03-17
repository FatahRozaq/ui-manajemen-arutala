<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Console\Scheduling\Event;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SertifkatController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\MasterMentorController;
use App\Http\Controllers\MasterPesertaController;
use App\Http\Controllers\AgendaPelatihanController;
use App\Http\Controllers\MasterPelatihanController;
use App\Http\Controllers\PesertaPelatihanController;
use App\Http\Controllers\Api\ApiPesertaPelatihanController;
use App\Http\Controllers\KelolaAdminController;

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
    return redirect('/login-page');
});

// Route::get('/', [TestController::class, 'index']);
Route::middleware([AuthCheck::class . ':admin'])->get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
// Route::middleware([AuthCheck::class . ':admin'])->get('/master-pelatihan', [MasterPelatihanController::class, 'index'])->name('pelatihan.index');
// Route::middleware([AuthCheck::class . ':admin'])->get('/form-pelatihan', [MasterPelatihanController::class, 'form'])->name('pelatihan.form');
// Route::middleware([AuthCheck::class . ':admin'])->get('/pelatihan/detail-pelatihan/{id}', [MasterPelatihanController::class, 'showPelatihan'])->name('pelatihan.showPelatihan');
// Route::middleware([AuthCheck::class . ':admin'])->get('/pelatihan/update-pelatihan', [MasterPelatihanController::class, 'updatePelatihan'])->name('pelatihan.updatePelatihan');

// Route::get('/agenda/detail', [AgendaPelatihanController::class, 'show'])->name('agenda.show');


// Admin Routes Group
Route::middleware([AuthCheck::class . ':admin'])->prefix('admin')->group(function () {
    // Admin - Peserta
    Route::prefix('pendaftar')->group(function () {
        Route::get('/', [MasterPesertaController::class, 'index'])->name('pendaftar.index');
        Route::get('/detail', [MasterPesertaController::class, 'detail'])->name('pendaftar.detail');
        Route::get('/edit', [MasterPesertaController::class, 'edit'])->name('pendaftar.edit');
        Route::get('/download-template', [MasterPesertaController::class, 'download'])->name('peserta.download');
    });

    // Admin - Mentor
    Route::prefix('mentor')->group(function () {
        Route::get('/', [MasterMentorController::class, 'index'])->name('mentor.index');
        Route::get('/detail', [MasterMentorController::class, 'detail'])->name('mentor.detail');
        Route::get('/tambah', [MasterMentorController::class, 'add'])->name('mentor.add');
        Route::get('/update', [MasterMentorController::class, 'update'])->name('mentor.update');
    });

    Route::prefix('kelola-admin')->group(function () {
        Route::get('/', [KelolaAdminController::class, 'index'])->name('admin.index');
        Route::get('/detail', [KelolaAdminController::class, 'show'])->name('admin.detail');
        Route::get('/tambah', [KelolaAdminController::class, 'add'])->name('admin.add');
        Route::get('/update', [KelolaAdminController::class, 'update'])->name('admin.update');
    });

    Route::prefix('pelatihan')->group(function () {
        Route::get('/', [MasterPelatihanController::class, 'index'])->name('pelatihan.index');
        Route::get('/tambah', [MasterPelatihanController::class, 'form'])->name('pelatihan.form');
        Route::get('/detail/{id}', [MasterPelatihanController::class, 'showPelatihan'])->name('pelatihan.showPelatihan');
        Route::get('/update', [MasterPelatihanController::class, 'updatePelatihan'])->name('pelatihan.updatePelatihan');
    });

    Route::prefix('agendapelatihan')->group(function () {
        Route::get('/', [AgendaPelatihanController::class, 'index'])->name('agenda.index');
        Route::get('/tambah', [AgendaPelatihanController::class, 'formAgenda'])->name('agenda.form');
        Route::get('/detail', [AgendaPelatihanController::class, 'show'])->name('agenda.show');
        Route::get('/update', [AgendaPelatihanController::class, 'updateAgenda'])->name('agenda.update');
    });

    Route::prefix('pesertapelatihan')->group(function () {
        Route::get('/', [PesertaPelatihanController::class, 'index'])->name('peserta.index');
        Route::get('/updatestatus', [PesertaPelatihanController::class, 'show'])->name('peserta.show');
    });

    Route::prefix('sertifikat')->group(function () {
        Route::get('/generateQR', [SertifkatController::class, 'generateQR'])->name('sertifikat.generateQR');
    });  
});

// Peserta Routes Group
Route::middleware([AuthCheck::class . ':pendaftar'])->prefix('peserta')->group(function () {

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('peserta.profile');
        Route::get('/update', [ProfileController::class, 'update'])->name('peserta.profile.update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('peserta.profile.password');
    });

    // Pendaftaran Event
    Route::prefix('pendaftaran')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index'])->name('peserta.pendaftaran');
    });

    // Sertifikat
    Route::prefix('sertifikat')->group(function () {
        Route::get('/', [SertifkatController::class, 'index'])->name('peserta.sertifikat');
    });
});

Route::get('sertifikat/{certificate_number}', [SertifkatController::class, 'show'])->name('peserta.sertifikat.show');

// Authentication
Route::get('/login-page', [AuthController::class, 'LoginPage'])->name('login.page');
Route::get('/register-page', [AuthController::class, 'RegisterPage'])->name('register.page');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', [AdminAuthController::class, 'login'])->name('admin.login');

// Route::middleware([AuthCheck::class . ':admin'])->get('/detailpelatihan', [MasterPelatihanController::class, 'show'])->name('pelatihan.show');
// Route::middleware([AuthCheck::class . ':admin'])->get('/form-agenda', [AgendaPelatihanController::class, 'formAgenda'])->name('agenda.form');
// Route::middleware([AuthCheck::class . ':admin'])->get('agenda/update-agenda', [AgendaPelatihanController::class, 'updateAgenda'])->name('agenda.update');
// Route::middleware([AuthCheck::class . ':admin'])->get('/agendapelatihan', [AgendaPelatihanController::class, 'index'])->name('agenda.index');





// Route::middleware([AuthCheck::class . ':admin'])->get('/updatestatus', [PesertaPelatihanController::class, 'show'])->name('peserta.show');

//Daftar Event
Route::middleware([AuthCheck::class . ':pendaftar'])->get('/daftar-event', [EventController::class, 'index'])->name('event.index');
Route::middleware([AuthCheck::class . ':pendaftar'])->get('/event/{id}',  [EventController::class, 'showEvent'])->name('event.detail');
Route::middleware([AuthCheck::class . ':pendaftar'])->get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');
Route::middleware([AuthCheck::class . ':pendaftar'])->get('/daftar-produk',  [EventController::class, 'showProduk'])->name('event.produk');

Route::post('/save-session', [AuthController::class, 'saveSession'])->name('save-session');
// Route::get('/daftar-event', [EventController::class, 'index'])->name('event.index');
// Route::get('/detail-event/{id}', [EventController::class, 'showEvent'])->name('detail.event');
// Route::get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');

Route::get('/export-peserta-pelatihan', [ApiPesertaPelatihanController::class, 'exportExcel']);

Route::get('/chart', function () {
    return view('generateChart');
});

Route::get('/kelola-dashboard', [DashboardController::class, 'dashboardDinamis']);
