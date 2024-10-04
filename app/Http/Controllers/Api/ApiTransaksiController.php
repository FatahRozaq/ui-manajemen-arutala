<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PendaftaranEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ApiTransaksiController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log request untuk debugging (bisa dihapus nanti)
        Log::info('Webhook received from Mayar: ', $request->all());

        // Validasi payload (jika ada tanda tangan atau token keamanan, bisa divalidasi di sini)
        $data = $request->all();

        // Misalnya data mengandung id_pendaftaran dan status pembayaran
        if (isset($data['id_pendaftaran']) && isset($data['status_pembayaran'])) {
            // Cari PendaftaranEvent berdasarkan id_pendaftaran
            $pendaftaran = PendaftaranEvent::find($data['id_pendaftaran']);

            if ($pendaftaran) {
                // Update status pembayaran
                $pendaftaran->status_pembayaran = $data['status_pembayaran'];
                $pendaftaran->save();

                return response()->json(['message' => 'Status pembayaran diperbarui'], 200);
            }

            return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Data tidak valid'], 400);
    }
}
