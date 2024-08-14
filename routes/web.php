<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use Illuminate\Console\Scheduling\Event;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterMentorController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifkatController;
use App\Http\Controllers\MasterPesertaController;
use App\Http\Controllers\MasterPelatihanController;

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
Route::prefix('peserta')->group(function () {

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
Route::get('/detailpelatihan', [MasterPelatihanController::class, 'show'])->name('pelatihan.show');
Route::get('/form-agenda', [MasterPelatihanController::class, 'agendaPelatihan'])->name('pelatihan.agenda');


//Daftar Event
Route::get('/daftar-event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/{id}',  [EventController::class, 'showEvent'])->name('event.detail');
Route::get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');
