<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiLamanPesertaController;
use App\Http\Controllers\Api\ApiMasterPelatihanController;
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
