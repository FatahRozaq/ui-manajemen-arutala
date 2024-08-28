<?php

use App\Http\Controllers\Api\ApiAuthController;
use Spatie\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiMentorController;
use App\Http\Controllers\Api\ApiProfilePeserta;
use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiLamanPesertaController;
use App\Http\Controllers\Api\ApiMasterPelatihanController;
use App\Http\Controllers\Api\ApiMasterPendaftar;
use App\Http\Controllers\Api\ApiPendaftaranEventController;
use App\Http\Controllers\Api\ApiPesertaPelatihanController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('mentor')->group(function () {
    Route::get('/', [ApiMentorController::class, 'index']);
    Route::post('/tambah', [ApiMentorController::class, 'store']);
    Route::get('/{id}', [ApiMentorController::class, 'show']);
    Route::put('/update/{id}', [ApiMentorController::class, 'update']);
    Route::delete('/delete/{id}', [ApiMentorController::class, 'destroy']);
});

Route::prefix('pendaftar')->group(function () {
    Route::get('/', [ApiMasterPendaftar::class, 'index']);
    Route::get('/{idPendaftar}', [ApiMasterPendaftar::class, 'show']);
    Route::get('/export/csv', [ApiMasterPendaftar::class, 'exportCsv']);
    Route::get('/export/excel', [ApiMasterPendaftar::class, 'exportExcel']);
    Route::post('/import/csv', [ApiMasterPendaftar::class, 'importCsv']);
    Route::post('/import/excel', [ApiMasterPendaftar::class, 'importExcel']);
    Route::delete('/{idPendaftar}', [ApiMasterPendaftar::class, 'delete']);
    Route::put('/{idPendaftar}', [ApiMasterPendaftar::class, 'update']);
});

Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::put('update', [ApiProfilePeserta::class, 'update']); 
    Route::get('/', [ApiProfilePeserta::class, 'show']); 
});

Route::middleware('auth:sanctum')->prefix('pendaftaran-event')->group(function () {
    Route::get('/{idAgenda}', [ApiPendaftaranEventController::class, 'show']);
    Route::post('/daftar', [ApiPendaftaranEventController::class, 'store']);
});


Route::get('/pelatihan/daftar-pelatihan', [ApiMasterPelatihanController::class, 'index']);
Route::post('/pelatihan/tambah-pelatihan', [ApiMasterPelatihanController::class, 'store']);
Route::put('/pelatihan/update-pelatihan/{id}', [ApiMasterPelatihanController::class, 'update']);
Route::get('/pelatihan/detail-pelatihan/{id}', [ApiMasterPelatihanController::class, 'show']);
Route::delete('/pelatihan/delete-pelatihan/{id}', [ApiMasterPelatihanController::class, 'destroy']);


Route::post('/agenda/tambah-agenda', [ApiAgendaController::class, 'storeAgenda']);
Route::put('/agenda/update-agenda/{id}', [ApiAgendaController::class, 'updateAgenda']);
Route::get('/agenda/detail-agenda/{id}', [ApiAgendaController::class, 'detailAgenda']);
Route::delete('/agenda/delete-agenda/{id}', [ApiAgendaController::class, 'deleteAgenda']);


Route::get('/peserta-pelatihan/agenda/{id_agenda}', [ApiPesertaPelatihanController::class, 'getPesertaByAgenda']);


Route::get('/laman-peserta/daftar-event', [ApiLamanPesertaController::class, 'getPelatihanDetails']);
Route::put('/peserta-pelatihan/update-status-pembayaran/{id_pendaftaran}', [ApiPesertaPelatihanController::class, 'updateStatusPembayaran']);
