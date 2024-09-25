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
            // Mendapatkan waktu sekarang
            $now = now();

            // Ambil ID user yang sedang login
            $idUser = auth('api')->id();

            // Ambil semua event yang diikuti oleh peserta ini
            $events = PendaftaranEvent::with(['agendaPelatihan.pelatihan'])
                ->where('id_peserta', $idUser)
                ->get();

            // Update status agenda pelatihan berdasarkan waktu sekarang
            $events->each(function ($event) use ($now) {
                $agenda = $event->agendaPelatihan;

                if ($now->lessThan($agenda->start_pendaftaran)) {
                    // Sebelum start_pendaftaran -> Planning
                    $agenda->update(['status' => 'Planning']);
                } elseif ($now->between($agenda->start_pendaftaran, $agenda->end_pendaftaran)) {
                    // Setelah start_pendaftaran dan sebelum end_pendaftaran -> Masa Pendaftaran
                    $agenda->update(['status' => 'Masa Pendaftaran']);
                } elseif ($now->greaterThan($agenda->end_pendaftaran) && $now->lessThan($agenda->start_date)) {
                    // Setelah end_pendaftaran dan sebelum start_date -> Pendaftaran Berakhir
                    $agenda->update(['status' => 'Pendaftaran Berakhir']);
                } elseif ($now->between($agenda->start_date, $agenda->end_date)) {
                    // Setelah start_date dan sebelum end_date -> Sedang Berlangsung
                    $agenda->update(['status' => 'Sedang Berlangsung']);
                } elseif ($now->greaterThan($agenda->end_date)) {
                    // Setelah end_date -> Selesai
                    $agenda->update(['status' => 'Selesai']);
                }
            });

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
