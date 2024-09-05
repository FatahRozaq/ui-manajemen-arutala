<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelatihan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use App\Models\PendaftaranEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiDashboardController extends Controller
{
    // Function untuk mendapatkan jumlah semua pendaftar
    public function getPendaftar(Request $request)
    {
        try {
            // Ambil parameter filter dari request
            $year = $request->input('year');
            $startMonth = $request->input('startMonth');
            $endMonth = $request->input('endMonth');

            // Query dasar
            $query = Pendaftar::where('is_deleted', false);

            // Jika ada filter, tambahkan kondisi
            if ($year) {
                $query->whereYear('created_time', $year);
            }
            if ($startMonth && $endMonth) {
                $query->whereMonth('created_time', '>=', $startMonth)
                    ->whereMonth('created_time', '<=', $endMonth);
            }

            // Hitung jumlah pendaftar berdasarkan filter
            $jumlahPendaftar = $query->count();

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

    public function getTotalPeserta(Request $request)
    {
        try {
            $idPelatihan = $request->input('id_pelatihan');
            $batch = $request->input('batch');

            // Mulai query untuk menghitung total peserta berdasarkan agenda pelatihan
            $query = DB::table('pendaftaran_event')
                ->join('agenda_pelatihan', 'pendaftaran_event.id_agenda', '=', 'agenda_pelatihan.id_agenda')
                ->join('pelatihan', 'agenda_pelatihan.id_pelatihan', '=', 'pelatihan.id_pelatihan')
                ->where('pendaftaran_event.is_deleted', false)
                ->where('agenda_pelatihan.is_deleted', false)
                ->where('pelatihan.is_deleted', false);

            // Filter berdasarkan id_pelatihan jika ada
            if ($idPelatihan) {
                $query->where('pelatihan.id_pelatihan', $idPelatihan);
            }

            // Filter berdasarkan batch jika ada
            if ($batch) {
                $query->where('agenda_pelatihan.batch', $batch);
            }

            // Menghitung total peserta berdasarkan id_agenda
            $totalPeserta = $query->select('pendaftaran_event.id_agenda', DB::raw('COUNT(pendaftaran_event.id_peserta) as total_peserta'))
                ->groupBy('pendaftaran_event.id_agenda')
                ->get();

            return response()->json([
                'total_peserta' => $totalPeserta,
                'message' => 'Total peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan total peserta',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function getPelatihanList()
    {
        try {
            $pelatihanList = Pelatihan::where('is_deleted', false)
                ->select('id_pelatihan', 'nama_pelatihan')
                ->get();

            return response()->json([
                'data' => $pelatihanList,
                'message' => 'Daftar pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan daftar pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBatchList(Request $request)
    {
        try {
            $idPelatihan = $request->input('id_pelatihan');

            $batchList = AgendaPelatihan::where('is_deleted', false)
                ->where('id_pelatihan', $idPelatihan)
                ->select('batch')
                ->distinct()
                ->get();

            return response()->json([
                'data' => $batchList,
                'message' => 'Daftar batch berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan daftar batch',
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
            // Ambil data pelatihan dan jumlah peserta per agenda per bulan
            $pelatihanTren = Pelatihan::where('is_deleted', false)
                ->with(['agendaPelatihan' => function ($query) {
                    $query->where('is_deleted', false)
                        ->with(['pendaftaranEvent' => function ($q) {
                            $q->select('id_agenda', DB::raw('DATE_TRUNC(\'month\', created_time) as bulan'), DB::raw('COUNT(id_peserta) as jumlah_peserta'))
                                ->where('pendaftaran_event.is_deleted', false)
                                ->groupBy('id_agenda', 'bulan');
                        }]);
                }])
                ->get()
                ->map(function ($pelatihan) {
                    return [
                        'id_pelatihan' => $pelatihan->id_pelatihan,
                        'nama_pelatihan' => $pelatihan->nama_pelatihan,
                        'agenda' => $pelatihan->agendaPelatihan->map(function ($agenda) {
                            return [
                                'id_agenda' => $agenda->id_agenda,
                                'batch' => $agenda->batch,
                                'jumlah_peserta_per_bulan' => $agenda->pendaftaranEvent->map(function ($event) {
                                    return [
                                        'bulan' => $event->bulan,
                                        'jumlah_peserta' => $event->jumlah_peserta
                                    ];
                                })
                            ];
                        })
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

    public function getPeserta()
    {
        try {
            $jumlahPeserta = PendaftaranEvent::where('is_deleted', false)->count();

            return response()->json([
                'jumlah_peserta' => $jumlahPeserta,
                'message' => 'Jumlah peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan jumlah peserta',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTopCities()
    {
        try {
            // Ambil 5 kota/kabupaten dengan jumlah pendaftar terbanyak
            $topCities = Pendaftar::select('kab_kota', DB::raw('COUNT(*) as jumlah'))
                ->where('is_deleted', false)
                ->groupBy('kab_kota')
                ->orderBy('jumlah', 'desc')
                ->limit(5)
                ->get();

            // Return response
            return response()->json([
                'top_cities' => $topCities,
                'message' => 'Top 5 kota/kabupaten berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data kota/kabupaten',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPelatihanCount()
    {
        try {
            // Menghitung jumlah pelatihan yang belum dihapus
            $jumlahPelatihan = AgendaPelatihan::where('is_deleted', false)->count();

            // Return response
            return response()->json([
                'jumlah_pelatihan' => $jumlahPelatihan,
                'message' => 'Jumlah pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan jumlah pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function trenJumlahPeserta()
    {
        try {
            // Mengambil data jumlah peserta berdasarkan tahun dan jenis pelatihan
            $trenPeserta = DB::table('pendaftaran_event')
                ->join('agenda_pelatihan', 'pendaftaran_event.id_agenda', '=', 'agenda_pelatihan.id_agenda')
                ->join('pelatihan', 'agenda_pelatihan.id_pelatihan', '=', 'pelatihan.id_pelatihan')
                ->select(
                    DB::raw('YEAR(agenda_pelatihan.start_date) as tahun'),
                    'pelatihan.nama_pelatihan',
                    DB::raw('COUNT(pendaftaran_event.id_pendaftaran) as jumlah_peserta')
                )
                ->where('pendaftaran_event.is_deleted', false)
                ->groupBy('tahun', 'pelatihan.nama_pelatihan')
                ->orderBy('tahun', 'asc')
                ->get();

            // Mengubah format data untuk grafik
            $data = [];
            foreach ($trenPeserta as $tren) {
                $data[$tren->nama_pelatihan][$tren->tahun] = $tren->jumlah_peserta;
            }

            // Mengembalikan response JSON
            return response()->json([
                'tren_jumlah_peserta' => $data,
                'message' => 'Tren jumlah peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            // Menangani kesalahan
            return response()->json([
                'message' => 'Gagal mendapatkan tren jumlah peserta',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
