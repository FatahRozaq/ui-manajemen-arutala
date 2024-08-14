<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterPelatihanController;
use App\Http\Controllers\MasterPesertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MasterMentorController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifkatController;

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

// Admin - Peserta
Route::get('admin/peserta', [MasterPesertaController::class, 'index'])->name('peserta.index');
Route::get('admin/peserta/detail', [MasterPesertaController::class, 'detail'])->name('peserta.detail');

// Admin - Mentor
Route::get('admin/mentor', [MasterMentorController::class, 'index'])->name('mentor.index');
Route::get('admin/mentor/detail', [MasterMentorController::class, 'detail'])->name('mentor.detail');
Route::get('admin/mentor/tambah', [MasterMentorController::class, 'add'])->name('mentor.add');
Route::get('admin/mentor/update', [MasterMentorController::class, 'update'])->name('mentor.update');

// Profile
Route::get('peserta/profile', [ProfileController::class, 'show'])->name('peserta.profile');
Route::get('peserta/profile/update', [ProfileController::class, 'update'])->name('peserta.profile.update');

// Pendaftaran Event
Route::get('peserta/pendaftaran', [PendaftaranController::class, 'index'])->name('peserta.pendaftaran');

// Sertifikat
Route::get('peserta/sertifikat', [SertifkatController::class, 'index'])->name('peserta.sertifikat');

// Authentication
Route::get('/login-page', [AuthController::class, 'LoginPage'])->name('login.page');
Route::get('/register-page', [AuthController::class, 'RegisterPage'])->name('register.page');
