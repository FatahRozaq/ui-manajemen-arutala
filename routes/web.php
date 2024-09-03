<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Console\Scheduling\Event;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SertifkatController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\MasterMentorController;
use App\Http\Controllers\MasterPesertaController;
use App\Http\Controllers\AgendaPelatihanController;
use App\Http\Controllers\MasterPelatihanController;
use App\Http\Controllers\PesertaPelatihanController;
use App\Http\Controllers\Api\ApiPesertaPelatihanController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [TestController::class, 'index']);
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/master-pelatihan', [MasterPelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/form-pelatihan', [MasterPelatihanController::class, 'form'])->name('pelatihan.form');
Route::get('/pelatihan/detail-pelatihan/{id}', [MasterPelatihanController::class, 'showPelatihan'])->name('pelatihan.showPelatihan');
Route::get('/pelatihan/update-pelatihan', [MasterPelatihanController::class, 'updatePelatihan'])->name('pelatihan.updatePelatihan');

Route::get('/agenda/detail', [AgendaPelatihanController::class, 'show'])->name('agenda.show');


// Admin Routes Group
Route::prefix('admin')->group(function () {

    // Admin - Peserta
    Route::prefix('peserta')->group(function () {
        Route::get('/', [MasterPesertaController::class, 'index'])->name('peserta.index');
        Route::get('/detail', [MasterPesertaController::class, 'detail'])->name('peserta.detail');
    });

    // Admin - Mentor
    Route::prefix('mentor')->group(function () {
        Route::get('/', [MasterMentorController::class, 'index'])->name('mentor.index');
        Route::get('/detail', [MasterMentorController::class, 'detail'])->name('mentor.detail');
        Route::get('/tambah', [MasterMentorController::class, 'add'])->name('mentor.add');
        Route::get('/update', [MasterMentorController::class, 'update'])->name('mentor.update');
    });
});

// Peserta Routes Group
Route::middleware('auth.check')->prefix('peserta')->group(function () {

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('peserta.profile');
        Route::get('/update', [ProfileController::class, 'update'])->name('peserta.profile.update');
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

// Authentication
Route::get('/login-page', [AuthController::class, 'LoginPage'])->name('login.page');
Route::get('/register-page', [AuthController::class, 'RegisterPage'])->name('register.page');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/detailpelatihan', [MasterPelatihanController::class, 'show'])->name('pelatihan.show');
Route::get('/form-agenda', [AgendaPelatihanController::class, 'formAgenda'])->name('agenda.form');
Route::get('agenda/update-agenda', [AgendaPelatihanController::class, 'updateAgenda'])->name('agenda.update');
Route::get('/agendapelatihan', [AgendaPelatihanController::class, 'index'])->name('agenda.index');

Route::get('/pesertapelatihan', [PesertaPelatihanController::class, 'index'])->name('peserta.index');
Route::get('/updatestatus', [PesertaPelatihanController::class, 'show'])->name('peserta.show');

//Daftar Event
Route::middleware('auth.check')->get('/daftar-event', [EventController::class, 'index'])->name('event.index');
Route::middleware('auth.check')->get('/event/{id}',  [EventController::class, 'showEvent'])->name('event.detail');
Route::middleware('auth.check')->get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');

Route::post('/save-session', [AuthController::class, 'saveSession'])->name('save-session');
Route::get('/daftar-event', [EventController::class, 'index'])->name('event.index');
Route::get('/detail-event/{id}', [EventController::class, 'showEvent'])->name('detail.event');
Route::get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');

Route::get('/export-peserta-pelatihan', [ApiPesertaPelatihanController::class, 'exportExcel']);
