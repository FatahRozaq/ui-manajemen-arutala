<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use App\Models\PendaftaranEvent;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PesertaPelatihanExport;


class ApiPesertaPelatihanController extends Controller
{
    public function getPesertaByAgenda(Request $request, $id_agenda)
    {
        try {
            // Cari agenda berdasarkan ID
            $agenda = AgendaPelatihan::with('pelatihan')->findOrFail($id_agenda);

            // Ambil data pendaftaran event yang terkait dengan agenda ini
            $pendaftaranEventsQuery = PendaftaranEvent::with('pendaftar')
                ->where('id_agenda', $id_agenda);

            // Filter berdasarkan nama pelatihan jika diberikan
            if ($request->has('nama_pelatihan')) {
                $pendaftaranEventsQuery->whereHas('agendaPelatihan.pelatihan', function ($query) use ($request) {
                    $query->where('nama_pelatihan', $request->input('nama_pelatihan'));
                });
            }

            // Filter berdasarkan batch jika diberikan
            if ($request->has('batch')) {
                $pendaftaranEventsQuery->whereHas('agendaPelatihan', function ($query) use ($request) {
                    $query->where('batch', $request->input('batch'));
                });
            }

            // **Tambahkan filter berdasarkan `id_pendaftaran` jika ada**
            if ($request->has('id_pendaftaran')) {
                $pendaftaranEventsQuery->where('id_pendaftaran', $request->input('id_pendaftaran'));
            }

            // Dapatkan data pendaftaran event berdasarkan filter
            $pendaftaranEvents = $pendaftaranEventsQuery->get();

            // Siapkan data response
            $data = $pendaftaranEvents->map(function ($event) use ($agenda) {
                return [
                    'id_pendaftaran' => $event->id_pendaftaran,
                    'id_agenda' => $event->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'batch' => $agenda->batch,
                    'nama_peserta' => $event->pendaftar->nama,
                    'no_kontak' => $event->pendaftar->no_kontak,
                    'status_pembayaran' => $event->status_pembayaran,
                ];
            });

            // Return response
            return response()->json([
                'data' => $data,
                'message' => 'Data peserta pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data peserta pelatihan',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getAgendaId(Request $request)
    {
        $namaPelatihan = $request->query('nama_pelatihan');
        $batch = $request->query('batch');

        if (empty($namaPelatihan) || empty($batch)) {
            return response()->json(['message' => 'Parameter nama_pelatihan atau batch hilang'], 400);
        }

        // Cari agenda berdasarkan nama pelatihan dan batch
        $agenda = AgendaPelatihan::whereHas('pelatihan', function ($query) use ($namaPelatihan) {
            $query->where('nama_pelatihan', $namaPelatihan);
        })
            ->where('batch', $batch)
            ->first();

        if ($agenda) {
            return response()->json(['id_agenda' => $agenda->id_agenda], 200);
        } else {
            return response()->json(['message' => 'Agenda tidak ditemukan'], 404);
        }
    }



    public function getPelatihanDanBatch()
    {
        try {
            // Ambil semua pelatihan yang ada
            $pelatihanList = AgendaPelatihan::with('pelatihan')
                ->where('is_deleted', false)
                ->get()
                ->groupBy('id_pelatihan')
                ->map(function ($agendaGroup) {
                    return [
                        'id_pelatihan' => $agendaGroup->first()->id_pelatihan,
                        'nama_pelatihan' => $agendaGroup->first()->pelatihan->nama_pelatihan,
                        'batches' => $agendaGroup->pluck('batch')->unique()->values()->toArray(),
                    ];
                })
                ->values();

            return response()->json([
                'data' => $pelatihanList,
                'message' => 'Data pelatihan dan batch berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data pelatihan dan batch',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatusPembayaran(Request $request, $id_pendaftaran)
    {
        try {
            // Validasi input
            $request->validate([
                'status_pembayaran' => 'required|string|max:255'
            ]);

            // Cari pendaftaran_event berdasarkan ID
            $pendaftaranEvent = PendaftaranEvent::findOrFail($id_pendaftaran);

            // Update status pembayaran
            $pendaftaranEvent->status_pembayaran = $request->input('status_pembayaran');
            $pendaftaranEvent->save();

            // Return response sukses
            return response()->json([
                'message' => 'Status pembayaran berhasil diupdate',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate status pembayaran',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        // Ambil parameter filter dari request
        $namaPelatihan = $request->query('nama_pelatihan');
        $batch = $request->query('batch');

        // Validasi parameter filter jika diperlukan
        if (empty($namaPelatihan) || empty($batch)) {
            return redirect()->back()->with('error', 'Filter pelatihan dan batch harus dipilih untuk ekspor.');
        }

        // Gunakan filter untuk mendapatkan data peserta yang sudah bayar dan belum bayar
        $pendaftaranEventsQuery = PendaftaranEvent::with('pendaftar')
            ->whereHas('agendaPelatihan.pelatihan', function ($query) use ($namaPelatihan) {
                $query->where('nama_pelatihan', $namaPelatihan);
            })
            ->whereHas('agendaPelatihan', function ($query) use ($batch) {
                $query->where('batch', $batch);
            });

        $pendaftaranEvents = $pendaftaranEventsQuery->get();

        // Ekspor data peserta yang sudah bayar dan belum bayar
        return Excel::download(new PesertaPelatihanExport($pendaftaranEvents), 'DataPesertaPelatihan.xlsx');
    }

    public function getAllPesertaPembayaran()
    {
        try {
            // Ambil semua data pendaftaran event beserta relasi yang diperlukan
            $pendaftaranEvents = PendaftaranEvent::with(['agendaPelatihan.pelatihan', 'pendaftar'])
                ->get();

            // Siapkan data response
            $data = $pendaftaranEvents->map(function ($event) {
                return [
                    'nama_pelatihan' => $event->agendaPelatihan->pelatihan->nama_pelatihan,
                    'batch' => $event->agendaPelatihan->batch,
                    'nama_peserta' => $event->pendaftar->nama,
                    'no_kontak' => $event->pendaftar->no_kontak,
                    'status_pembayaran' => $event->status_pembayaran,
                ];
            });

            // Return response
            return response()->json([
                'data' => $data,
                'message' => 'Data pembayaran peserta berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data pembayaran peserta',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
