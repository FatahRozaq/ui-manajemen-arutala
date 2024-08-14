<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use Illuminate\Console\Scheduling\Event;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterMentorController;
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
Route::get('/peserta', [MasterPesertaController::class, 'index'])->name('peserta.index');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/master-pelatihan', [MasterPelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/form-pelatihan', [MasterPelatihanController::class, 'form'])->name('pelatihan.form');
Route::get('/mentor', [MasterMentorController::class, 'index'])->name('mentor.index');
Route::get('/detailpelatihan', [MasterPelatihanController::class, 'show'])->name('pelatihan.show');
Route::get('/form-agenda', [MasterPelatihanController::class, 'agendaPelatihan'])->name('pelatihan.agenda');


//Daftar Event
Route::get('/daftar-event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/{id}',  [EventController::class, 'showEvent'])->name('event.detail');
Route::get('/my-event',  [EventController::class, 'myEvent'])->name('event.history');
