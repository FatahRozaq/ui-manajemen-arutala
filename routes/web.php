<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MasterMentorController;
use App\Http\Controllers\MasterPesertaController;

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
    return view('welcome');
});

Route::get('/', [TestController::class, 'index']);
Route::get('/peserta', [MasterPesertaController::class, 'index'])->name('peserta.index');
Route::get('/mentor', [MasterMentorController::class, 'index'])->name('mentor.index');
