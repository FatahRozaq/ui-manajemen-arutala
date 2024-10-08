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
    public function sendWebhookTest()
    {
        $url = 'https://19ce-182-253-123-20.ngrok-free.app/api/webhook-handler';  // URL Ngrok kamu
        $apiKey = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiIzNDEzMjc4OC0zMmUyLTQzMzQtYTZhMC0zOGRiNzllMDE5MGMiLCJhY2NvdW50SWQiOiI2MzhjZWRiYS03NDBhLTQ1NjItODM5MC04MDc1MjE4MzFlMDYiLCJjcmVhdGVkQXQiOiIxNzI4Mjc1ODcwOTg2Iiwicm9sZSI6ImRldmVsb3BlciIsInN1YiI6ImFydXRhbGEubW1AZ21haWwuY29tIiwibmFtZSI6IkFydXRhbGFMYWIiLCJsaW5rIjoiYWRtaW4tYXJ1dGFsYWxhYiIsImlzU2VsZkRvbWFpbiI6bnVsbCwiaWF0IjoxNzI4Mjc1ODcwfQ.XRHSh2Bap4_fiQ-NMjohMzC4CmV_9pwxb1OhdBPTWp2qocZz0hKUhGWCVySo-tYepDviIJtGztae-L_PIwkVmXwirceieiD28TRdij5zOCc7E_0GeTqfPW6Z_8z3Uv98NQPqmlgFj5Rq277_h_zUPEgZFi9OpSM6izvCLKLo9SKK2e3K7tdACW_WfcFijwRVuNBUjWOd4bSfHRse7ry8URTEA-f7XXjw_Y16QwiKFFoaX0fzA4tmcyH8ex0ISJv5vNRdRh6wG3gYbsle8T6_rUafsxBvhOg6EQYH20mIoEkqFhx4V00kaMJIrSJ2ZRX7sroFUpJSbQNoZB3O2RS5aA';

        // Tambahkan header ngrok-skip-browser-warning
        $response = Http::withHeaders([
                'ngrok-skip-browser-warning' => 'true'
            ])->withToken($apiKey)
            ->post($url, [
                'urlHook' => 'https://19ce-182-253-123-20.ngrok-free.app/api/webhook-handler'
            ]);

        // Cek jika request sukses
        if ($response->successful()) {
            return $response->body();
        }

        // Tangani jika request gagal
        return response()->json(['error' => 'Failed to send webhook'], 500);
    }
    // public function handleWebhook(Request $request)
    // {
    //     return response()->json(['message' => 'done']);
    // }
    public function handleWebhook(Request $request)
    {
        // Log informasi permintaan untuk debugging
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        // Periksa apakah header ngrok-skip-browser-warning ada
        if (!$request->header('ngrok-skip-browser-warning')) {
            return response()->json(['message' => 'Header ngrok-skip-browser-warning tidak ada'], 400);
        }

        // Validasi payload dari Mayar
        $data = $request->input('data', []);

        // Periksa apakah data memiliki kunci yang diperlukan
        if (isset($data['status']) && isset($data['customerEmail'])) {
            if ($data['status'] === 'SUCCESS') {
                $email = $data['customerEmail'];

                // Cari pendaftar berdasarkan email
                $pendaftar = Pendaftar::where('email', $email)->first();

                if ($pendaftar) {
                    // Update status pembayaran di tabel pendaftaran_event
                    PendaftaranEvent::where('id_peserta', $pendaftar->id_pendaftar)
                        ->update(['status_pembayaran' => 'Unpaid']);

                    return response()->json(['message' => 'Status pembayaran telah diperbarui'], 200);
                } else {
                    return response()->json(['message' => 'Pendaftar tidak ditemukan'], 404);
                }
            } else {
                return response()->json(['message' => 'Pembayaran gagal atau belum selesai'], 400);
            }
        }

        return response()->json(['message' => 'Payload tidak valid'], 400);
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
            'Authorization' => 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiIzNDEzMjc4OC0zMmUyLTQzMzQtYTZhMC0zOGRiNzllMDE5MGMiLCJhY2NvdW50SWQiOiI2MzhjZWRiYS03NDBhLTQ1NjItODM5MC04MDc1MjE4MzFlMDYiLCJjcmVhdGVkQXQiOiIxNzI4MzUzMjg3OTI0Iiwicm9sZSI6ImRldmVsb3BlciIsInN1YiI6ImFydXRhbGEubW1AZ21haWwuY29tIiwibmFtZSI6IkFydXRhbGFMYWIiLCJsaW5rIjoiYWRtaW4tYXJ1dGFsYWxhYiIsImlzU2VsZkRvbWFpbiI6bnVsbCwiaWF0IjoxNzI4MzUzMjg3fQ.J8Oh0t-XOMY2vcElO6mucQ94QCHdHZ_HHDKngDJhL8zt2pFN4warAd23gaUn-pbTG0VGDn-yNxH5BJPbJUYzGW8JPVW24q-zRRNWL3zpoq78LS8clU5sDMPrRHwXpjO7ytMUhY7ajKLinnXD_uTeshS7lgiS-XlHNV-8wikRIYrROotuPmb57PkOv_B1ISW8ReiRHmdDxYJyGFeSCVUKgVmMfv_0hqukPSxSZwlUkJGZd0CUGkd4JGBGc0oTcvlrx88Y0Tr2qXaSqp1JDRwaE2fU6DxPTbaBtJ-8uF1Ck4lHaPMInllaXZwiJvPYLUKhuR_QrDc4aj6wRUR-VBar_Q',
        ])->post('https://api.mayar.id/hl/v1/webhook/register', [
            'urlHook' => 'https://example.mayar.com'
        ]);

        return response()->json(json_decode($response->body()));
    }

    // Fungsi untuk test webhook
    public function testWebhook()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiIzNDEzMjc4OC0zMmUyLTQzMzQtYTZhMC0zOGRiNzllMDE5MGMiLCJhY2NvdW50SWQiOiI2MzhjZWRiYS03NDBhLTQ1NjItODM5MC04MDc1MjE4MzFlMDYiLCJjcmVhdGVkQXQiOiIxNzI4MzUzMjg3OTI0Iiwicm9sZSI6ImRldmVsb3BlciIsInN1YiI6ImFydXRhbGEubW1AZ21haWwuY29tIiwibmFtZSI6IkFydXRhbGFMYWIiLCJsaW5rIjoiYWRtaW4tYXJ1dGFsYWxhYiIsImlzU2VsZkRvbWFpbiI6bnVsbCwiaWF0IjoxNzI4MzUzMjg3fQ.J8Oh0t-XOMY2vcElO6mucQ94QCHdHZ_HHDKngDJhL8zt2pFN4warAd23gaUn-pbTG0VGDn-yNxH5BJPbJUYzGW8JPVW24q-zRRNWL3zpoq78LS8clU5sDMPrRHwXpjO7ytMUhY7ajKLinnXD_uTeshS7lgiS-XlHNV-8wikRIYrROotuPmb57PkOv_B1ISW8ReiRHmdDxYJyGFeSCVUKgVmMfv_0hqukPSxSZwlUkJGZd0CUGkd4JGBGc0oTcvlrx88Y0Tr2qXaSqp1JDRwaE2fU6DxPTbaBtJ-8uF1Ck4lHaPMInllaXZwiJvPYLUKhuR_QrDc4aj6wRUR-VBar_Q',
        ])->post('https://api.mayar.id/hl/v1/webhook/test', [
            'urlHook' => 'https://atms.arutalalab.net/api/mayar/webhook'
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
