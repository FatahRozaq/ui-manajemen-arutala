<?php

use Spatie\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiProfilePeserta;
use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiMentorController;
use App\Http\Controllers\Api\ApiMyEventController;
use App\Http\Controllers\Api\ApiDashboardController;
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

Route::middleware('auth:api')->prefix('profile')->group(function () {
    Route::put('update', [ApiProfilePeserta::class, 'update']);
    Route::get('/', [ApiProfilePeserta::class, 'show']);
});

Route::middleware('auth:api')->prefix('pendaftaran-event')->group(function () {
    Route::get('/{idAgenda}', [ApiPendaftaranEventController::class, 'show']);
    Route::post('/daftar', [ApiPendaftaranEventController::class, 'store']);
});


Route::prefix('pelatihan')->group(function () {
    Route::get('/daftar-pelatihan', [ApiMasterPelatihanController::class, 'index']);
    Route::post('/tambah-pelatihan', [ApiMasterPelatihanController::class, 'store']);
    Route::put('/update-pelatihan/{id}', [ApiMasterPelatihanController::class, 'update']);
    Route::get('/detail-pelatihan/{id}', [ApiMasterPelatihanController::class, 'show'])->name('pelatihan.showPelatihan');
    Route::delete('/delete-pelatihan/{id}', [ApiMasterPelatihanController::class, 'destroy']);
});


Route::prefix('agenda')->group(function () {
    Route::get('/index', [ApiAgendaController::class, 'index']);
    Route::post('/tambah-agenda', [ApiAgendaController::class, 'storeAgenda'])->name('agenda.tambah');
    Route::put('/update-agenda/{id}', [ApiAgendaController::class, 'updateAgenda']);
    Route::get('/detail-agenda/{id}', [ApiAgendaController::class, 'detailAgenda']);
    Route::delete('/delete-agenda/{id}', [ApiAgendaController::class, 'deleteAgenda']);
});

Route::get('/pelatihan-mentor-data', [ApiAgendaController::class, 'getPelatihanMentorData']);




Route::prefix('peserta-pelatihan')->group(function () {
    Route::get('/agenda/{id_agenda}/peserta', [ApiPesertaPelatihanController::class, 'getPesertaByAgenda']);
    Route::put('/update-status-pembayaran/{id_pendaftaran}', [ApiPesertaPelatihanController::class, 'updateStatusPembayaran']);
});

Route::get('/peserta-pelatihan/pelatihan-batch', [ApiPesertaPelatihanController::class, 'getPelatihanDanBatch']);
Route::get('/peserta-pelatihan/get-agenda-id', [ApiPesertaPelatihanController::class, 'getAgendaId']);
Route::get('/peserta-pelatihan/export', [ApiPesertaPelatihanController::class, 'exportExcel']);


Route::prefix('laman-peserta')->group(function () {
    Route::get('/daftar-event', [ApiLamanPesertaController::class, 'getPelatihanDetails']);
    Route::get('/event-detail/{id}', [ApiLamanPesertaController::class, 'getEventDetail'])->name('laman-peserta.event-detail');
});

Route::prefix('my-events')->group(function () {
    Route::get('/{id_peserta}', [ApiMyEventController::class, 'getMyEvents']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/pendaftar', [ApiDashboardController::class, 'getPendaftar']);
    Route::get('/tren-pelatihan', [ApiDashboardController::class, 'trenPelatihan']);
    Route::get('/top-provinces', [ApiDashboardController::class, 'getTopProvinces']);
    Route::get('/universities-participants', [ApiDashboardController::class, 'getUniversitiesParticipants']);
    Route::get('/companies-participants', [ApiDashboardController::class, 'getCompaniesParticipants']);
    Route::get('/participants-by-activity', [ApiDashboardController::class, 'getParticipantsByActivity']);
    Route::get('/total-peserta', [ApiDashboardController::class, 'getTotalPeserta']);
    Route::get('/pelatihan-list', [ApiDashboardController::class, 'getPelatihanList']);
    Route::get('/batch-list', [ApiDashboardController::class, 'getBatchList']);
    Route::get('/top-city', [ApiDashboardController::class, 'getTopCities']);

    Route::get('/total-pelatihan', [ApiDashboardController::class, 'getPelatihanCount']);
});
