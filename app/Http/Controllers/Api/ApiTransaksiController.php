<?php

namespace App\Http\Controllers\Api;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\PendaftaranEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiTransaksiController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validasi payload dari Mayar (pastikan sesuai dengan struktur yang dikirim oleh Mayar)
        $data = $request->all();
        
        if ($data['status'] === 'success') {
            // Ambil email dari payload
            $email = $data['email'];

            // Cari pendaftar berdasarkan email
            $pendaftar = Pendaftar::where('email', $email)->first();

            if ($pendaftar) {
                // Update status pembayaran di tabel pendaftaran_event
                PendaftaranEvent::where('id_peserta', $pendaftar->id_pendaftar)
                    ->update(['status_pembayaran' => 'paid']);

                return response()->json(['message' => 'Status pembayaran telah diperbarui'], 200);
            } else {
                return response()->json(['message' => 'Pendaftar tidak ditemukan'], 404);
            }
        } else {
            return response()->json(['message' => 'Pembayaran gagal atau belum selesai'], 400);
        }
    }
    // public function handleWebhook(Request $request)
    // {
    //     // Log request untuk debugging (bisa dihapus nanti)
    //     Log::info('Webhook received from Mayar: ', $request->all());

    //     // Validasi payload (jika ada tanda tangan atau token keamanan, bisa divalidasi di sini)
    //     $data = $request->all();

    //     // Misalnya data mengandung id_pendaftaran dan status pembayaran
    //     if (isset($data['id_pendaftaran']) && isset($data['status_pembayaran'])) {
    //         // Cari PendaftaranEvent berdasarkan id_pendaftaran
    //         $pendaftaran = PendaftaranEvent::find($data['id_pendaftaran']);

    //         if ($pendaftaran) {
    //             // Update status pembayaran
    //             $pendaftaran->status_pembayaran = $data['status_pembayaran'];
    //             $pendaftaran->save();

    //             return response()->json(['message' => 'Status pembayaran diperbarui'], 200);
    //         }

    //         return response()->json(['message' => 'Pendaftaran tidak ditemukan'], 404);
    //     }

    //     return response()->json(['message' => 'Data tidak valid'], 400);
    // }

    // Fungsi untuk register webhook
    public function registerWebhook()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->post('https://api.mayar.id/hl/v1/webhook/register', [
            'urlHook' => 'https://example.mayar.com'
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk test webhook
    public function testWebhook()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->post('https://api.mayar.id/hl/v1/webhook/test', [
            'urlHook' => 'https://example.mayar.com'
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk mendapatkan daftar transaksi
    public function getTransactions()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->get('https://api.mayar.id/hl/v1/transactions', [
            'page' => 1,
            'pageSize' => 10
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk mendapatkan transaksi yang belum dibayar
    public function getUnpaidTransactions()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->get('https://api.mayar.id/hl/v1/transactions/unpaid', [
            'page' => 1,
            'pageSize' => 10
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk membuat QR code baru
    public function createQRCode()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->post('https://api.mayar.id/hl/v1/qrcode/create', [
            'amount' => 10000
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk mendapatkan QR code statis
    public function getStaticQRCode()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Paste-Your-API-Key-Here',
        ])->get('https://api.mayar.id/hl/v1/qrcode/static', [
            'amount' => 10000
        ]);

        return response()->json(json_decode($response->body()));
    }
}
