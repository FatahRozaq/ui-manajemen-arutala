<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran_Event;
use App\Models\AgendaPelatihan;

class ApiPesertaPelatihanController extends Controller
{
    public function getPesertaByAgenda($id_agenda)
    {
        try {
            // Cari agenda berdasarkan ID
            $agenda = AgendaPelatihan::with('pelatihan')->findOrFail($id_agenda);

            // Ambil data pendaftaran event yang terkait dengan agenda ini
            $pendaftaranEvents = Pendaftaran_Event::with('pendaftar')
                ->where('id_agenda', $id_agenda)
                ->get();

            // Siapkan data response
            $data = $pendaftaranEvents->map(function ($event) use ($agenda) {
                return [
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

    public function updateStatusPembayaran(Request $request, $id_pendaftaran)
    {
        try {
            // Validasi input
            $request->validate([
                'status_pembayaran' => 'required|string|max:255'
            ]);

            // Cari pendaftaran_event berdasarkan ID
            $pendaftaranEvent = Pendaftaran_Event::findOrFail($id_pendaftaran);

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
}
