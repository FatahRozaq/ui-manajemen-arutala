<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelatihan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\PendaftaranEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiDashboardController extends Controller
{
    // Function untuk mendapatkan jumlah semua pendaftar
    public function getPendaftar()
    {
        try {
            $jumlahPendaftar = Pendaftar::where('is_deleted', false)->count();

            return response()->json([
                'jumlah_pendaftar' => $jumlahPendaftar,
                'message' => 'Jumlah pendaftar berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan jumlah pendaftar',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Function untuk mendapatkan tren pelatihan
    public function trenPelatihan()
    {
        try {
            $pelatihanTren = Pelatihan::where('is_deleted', false)
                ->withCount(['agendaPelatihan as jumlah_peserta' => function ($query) {
                    $query->join('pendaftaran_event', 'agenda_pelatihan.id_agenda', '=', 'pendaftaran_event.id_agenda')
                        ->where('pendaftaran_event.is_deleted', false);
                }])
                ->get()
                ->map(function ($pelatihan) {
                    return [
                        'id_pelatihan' => $pelatihan->id_pelatihan,
                        'nama_pelatihan' => $pelatihan->nama_pelatihan,
                        'jumlah_peserta' => $pelatihan->jumlah_peserta
                    ];
                });

            return response()->json([
                'tren_pelatihan' => $pelatihanTren,
                'message' => 'Tren pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan tren pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTopProvinces()
    {
        try {
            // Ambil 5 provinsi dengan jumlah pendaftar terbanyak
            $topProvinces = Pendaftar::select('provinsi', DB::raw('COUNT(*) as jumlah'))
                ->where('is_deleted', false)
                ->groupBy('provinsi')
                ->orderBy('jumlah', 'desc')
                ->limit(5)
                ->get();

            // Return response
            return response()->json([
                'top_provinces' => $topProvinces,
                'message' => 'Top 5 provinsi berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data provinsi',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUniversitiesParticipants()
    {
        try {
            // Mengelompokkan data berdasarkan nama_instansi dengan aktivitas 'Mahasiswa'
            $universities = Pendaftar::where('aktivitas', 'Mahasiswa')
                ->whereNotNull('nama_instansi')
                ->where('is_deleted', false)
                ->groupBy('nama_instansi')
                ->selectRaw('nama_instansi, COUNT(*) as total_peserta')
                ->orderBy('total_peserta', 'desc')
                ->get();

            // Return response dengan data universitas dan jumlah peserta
            return response()->json([
                'data' => $universities,
                'message' => 'Data perguruan tinggi dan jumlah peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data perguruan tinggi',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCompaniesParticipants()
    {
        try {
            // Mengelompokkan data berdasarkan nama_instansi dengan aktivitas selain 'Mahasiswa'
            $companies = Pendaftar::where('aktivitas', '!=', 'Mahasiswa')
                ->whereNotNull('nama_instansi')
                ->where('is_deleted', false)
                ->groupBy('nama_instansi')
                ->selectRaw('nama_instansi, COUNT(*) as total_peserta')
                ->orderBy('total_peserta', 'desc')
                ->get();

            // Return response dengan data perusahaan dan jumlah peserta
            return response()->json([
                'data' => $companies,
                'message' => 'Data perusahaan dan jumlah peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data perusahaan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getParticipantsByActivity()
    {
        try {
            // Mengelompokkan data berdasarkan aktivitas
            $activities = Pendaftar::where('is_deleted', false)
                ->groupBy('aktivitas')
                ->selectRaw('aktivitas, COUNT(*) as total_peserta')
                ->orderBy('total_peserta', 'desc')
                ->get();

            // Return response dengan data aktivitas dan jumlah peserta
            return response()->json([
                'data' => $activities,
                'message' => 'Data peserta berdasarkan aktivitas berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data peserta berdasarkan aktivitas',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
