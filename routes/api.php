<?php

use Spatie\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthAdmin;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiProfilePeserta;
use App\Http\Controllers\Api\ApiMasterPendaftar;
use App\Http\Controllers\Api\ApiAgendaController;
use App\Http\Controllers\Api\ApiMentorController;
use App\Http\Controllers\Api\ApiMyEventController;
use App\Http\Controllers\Api\ApiAuthAdminController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiTransaksiController;
use App\Http\Controllers\Api\ApiSertifikatController;
use App\Http\Controllers\Api\ApiKelolaAdminController;
use App\Http\Controllers\Api\ApiLamanPesertaController;
use App\Http\Controllers\Api\ApiKelolaDashboardController;
use App\Http\Controllers\Api\ApiMasterPelatihanController;
use App\Http\Controllers\Api\ApiPendaftaranEventController;
use App\Http\Controllers\Api\ApiPesertaPelatihanController;
use App\Http\Controllers\Api\Dashboard\ApiGetDataController;
use App\Http\Controllers\Api\Dashboard\ApiTableDataController;
use App\Http\Controllers\Api\Dashboard\ApiTableDashboardController;

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
Route::post('/logout', [ApiAuthController::class, 'logout']);

Route::post('/register-admin', [ApiAuthAdminController::class, 'register']);
Route::post('/login-admin', [ApiAuthAdminController::class, 'login']);
Route::post('/logout-admin', [ApiAuthAdminController::class, 'logout']);

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

Route::get('/peserta/download-template', [ApiMasterPendaftar::class, 'downloadTemplate'])->name('api.peserta.download');

Route::middleware('auth:api')->prefix('profile')->group(function () {
    Route::put('update', [ApiProfilePeserta::class, 'update']);
    Route::get('/', [ApiProfilePeserta::class, 'show']);
    Route::post('/change-password', [ApiProfilePeserta::class, 'changePassword']);
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

    Route::get('/pelatihan/batches/{namaPelatihan}', [ApiSertifikatController::class, 'getBatchesByPelatihan']);
    Route::get('/pelatihan-data', [ApiAgendaController::class, 'getPelatihanData']);
});

Route::get('/pelatihan-mentor-data', [ApiAgendaController::class, 'getPelatihanMentorData']);
Route::post('/peserta-pelatihan/export-filtered', [ApiPesertaPelatihanController::class, 'exportFiltered']);

Route::prefix('peserta-pelatihan')->group(function () {
    Route::get('/agenda/{id_agenda}/peserta', [ApiPesertaPelatihanController::class, 'getPesertaByAgenda']);
    Route::put('/update-status-pembayaran/{id_pendaftaran}', [ApiPesertaPelatihanController::class, 'updateStatusPembayaran']);
});
Route::get('/peserta-pembayaran', [ApiPesertaPelatihanController::class, 'getAllPesertaPembayaran']);
Route::get('/peserta-pelatihan/pelatihan-batch', [ApiPesertaPelatihanController::class, 'getPelatihanDanBatch']);
Route::get('/peserta-pelatihan/get-agenda-id', [ApiPesertaPelatihanController::class, 'getAgendaId']);
Route::get('/peserta-pelatihan/export', [ApiPesertaPelatihanController::class, 'exportExcel']);
Route::get('/peserta-pelatihan/get-agenda-ids', [ApiPesertaPelatihanController::class, 'getAgendaIds']);
Route::get('/peserta-pelatihan/by-pelatihan', [ApiPesertaPelatihanController::class, 'getPesertaByPelatihan']);



Route::middleware('auth:api')->prefix('laman-peserta')->group(function () {
    Route::get('/daftar-event', [ApiLamanPesertaController::class, 'getPelatihanDetails']);
    Route::get('/event-detail/{id}', [ApiLamanPesertaController::class, 'getEventDetail'])->name('laman-peserta.event-detail');
});

Route::middleware('auth:api')->prefix('my-events')->group(function () {
    Route::get('/', [ApiMyEventController::class, 'getMyEvents']);
});

Route::middleware('auth:api')->group(function () {
    // Route untuk API notifikasi event yang belum dibayar
    Route::get('/my-notifications', [ApiMyEventController::class, 'getMyNotifications']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/pendaftar', [ApiDashboardController::class, 'getPendaftar']);
    Route::get('/tren-pelatihan', [ApiDashboardController::class, 'trenPelatihan']);
    Route::get('/top-provinces', [ApiDashboardController::class, 'getTopProvinces']);
    Route::get('/universities-participants', [ApiDashboardController::class, 'getUniversitiesParticipants']);
    Route::get('/companies-participants', [ApiDashboardController::class, 'getCompaniesParticipants']);
    Route::get('/participants-by-activity', [ApiDashboardController::class, 'getParticipantsByActivity']);
    // Route::get('/total-peserta', [ApiDashboardController::class, 'getTotalPeserta']);
    Route::get('/pelatihan-list', [ApiDashboardController::class, 'getPelatihanList']);
    Route::get('/batch-list', [ApiDashboardController::class, 'getBatchList']);

    Route::get('/top-city', [ApiDashboardController::class, 'getTopCities']);

    Route::get('/total-pelatihan', [ApiDashboardController::class, 'getPelatihanCount']);
    Route::get('/total-pelatihan', [ApiDashboardController::class, 'getPelatihanCount']);

    Route::get('/training-agenda', [ApiDashboardController::class, 'getTrainingAgenda']);

    Route::get('/total-peserta', [ApiDashboardController::class, 'getTotalPesertaByPelatihanAndBatch']);
});

Route::prefix('kelola-admin')->group(function () {
    Route::get('/', [ApiKelolaAdminController::class, 'index']);
    Route::get('/{id}', [ApiKelolaAdminController::class, 'show']);
    Route::put('update/{id}', [ApiKelolaAdminController::class, 'update']);
    Route::delete('delete/{id}', [ApiKelolaAdminController::class, 'destroy']);
});

Route::middleware('auth:admin')->prefix('admin-profile')->group(function () {
    Route::get('/', [ApiAuthAdminController::class, 'show']);
});

Route::prefix('sertifikat')->group(function () {
    Route::post('/upload-kompetensi', [ApiSertifikatController::class, 'uploadKompetensi']);
    Route::get('/download-kompetensi', [ApiSertifikatController::class, 'downloadKompetensi']);
    Route::get('/view-kompetensi/{idPendaftaran}', [ApiSertifikatController::class, 'viewKompetensi']);

    Route::post('/upload-kehadiran', [ApiSertifikatController::class, 'uploadKehadiran']);
    Route::get('/download-kehadiran', [ApiSertifikatController::class, 'downloadKehadiran']);
    Route::get('/view-kehadiran/{idPendaftaran}', [ApiSertifikatController::class, 'viewKehadiran']);

    Route::get('/check/{id_pendaftaran}', [ApiSertifikatController::class, 'checkSertifikat']);

    Route::middleware('auth:api')->get('/peserta', [ApiSertifikatController::class, 'sertifikatPendaftar']);

    Route::post('/generateQR', [ApiSertifikatController::class, 'generateQR']);
    Route::get('/detail-sertifikat/{certificateNumber}', [ApiSertifikatController::class, 'detailSertifikat']);
});

Route::get('/produk', [ApiLamanPesertaController::class, 'getProduk']);
Route::post('/mayar/webhook', [ApiTransaksiController::class, 'handleWebhook']);
Route::post('/test-webhook', [ApiTransaksiController::class, 'sendWebhookTest']);
Route::get('/test-balance', [ApiTransaksiController::class, 'balance']);
Route::get('/get-transaction', [ApiTransaksiController::class, 'dataTransaksi']);

Route::prefix('kelola-dashboard')->group(function () {
    Route::post('/convert-sql', [ApiKelolaDashboardController::class, 'convertSql']);
    Route::get('/tables', [ApiGetDataController::class, 'getAllTables']);
    Route::get('/columns/{table}', [ApiGetDataController::class, 'getTableColumns']);
    // Route::post('/table-data/{table}', [ApiGetDataController::class, 'getTableDataByColumns']);
    Route::post('/table-data/{table}', [ApiGetDataController::class, 'getTableDataByColumns']);
    Route::post('/execute-query', [ApiGetDataController::class, 'executeQuery']);
    // Route::post('/table-data', [ApiGetDataController::class, 'getTableDataByColumns']);
    // Route::get('/table-data/{table}', [ApiTableDataController::class, 'getTableData']);
});
