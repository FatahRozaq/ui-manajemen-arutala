<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranEvent;

class ApiMyEventController extends Controller
{
    public function getMyEvents()
    {
        try {

            $idUser = auth('api')->id();
            // Ambil semua event yang diikuti oleh peserta ini
            $events = PendaftaranEvent::with(['agendaPelatihan.pelatihan'])
                ->where('id_peserta', $idUser)
                ->get();

            // Siapkan data response
            $data = $events->map(function ($event) {
                return [
                    'id_pendaftaran' => $event->id_pendaftaran,
                    'id_peserta' => $event->id_peserta,
                    'nama_pelatihan' => $event->agendaPelatihan->pelatihan->nama_pelatihan,
                    'start_date' => $event->agendaPelatihan->start_date,
                    'end_date' => $event->agendaPelatihan->end_date,
                    'status_pelatihan' => $event->agendaPelatihan->status,
                    'status_pembayaran' => $event->status_pembayaran,
                    'is_deleted' => $event->is_deleted,
                ];
            });

            // Return response
            return response()->json([
                'data' => $data,
                'message' => 'Data event berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data event',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
