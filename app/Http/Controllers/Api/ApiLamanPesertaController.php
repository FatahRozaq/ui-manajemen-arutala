<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Mentor;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use Illuminate\Support\Facades\Log;
use App\Models\PendaftaranEvent;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApiLamanPesertaController extends Controller
{
    public function getPelatihanDetails(Request $request)
    {
        try {
            // Ambil ID user yang sedang login
            $userId = auth('api')->id();

            // Get the current date
            $currentDate = Carbon::now();

            // Logging for debugging
            Log::info('User ID:', ['userId' => $userId]);
            Log::info('Current Date:', ['currentDate' => $currentDate]);

            // Fetch agendas with open registration status
            $agendas = AgendaPelatihan::with('pelatihan')
                ->where('start_pendaftaran', '<=', $currentDate)
                ->where('end_pendaftaran', '>=', $currentDate)
                ->where('is_deleted', false)
                ->where('status', 'Masa Pendaftaran')
                ->orderBy('start_date', 'desc')
                ->get();

            // Prepare response data
            $data = $agendas->map(function ($agenda) use ($userId) {
                // Check if the user is already registered for this event
                $isRegistered = PendaftaranEvent::where('id_peserta', $userId)
                    ->where('id_agenda', $agenda->id_agenda)
                    ->exists();

                return [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $agenda->poster_agenda,
                    'deskripsi' => $agenda->pelatihan->deskripsi,
                    'batch' => $agenda->batch,
                    'investasi' => $agenda->investasi,
                    'sesi' => $agenda->sesi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'is_deleted' => $agenda->is_deleted,
                    'is_registered' => $isRegistered, // Add registration status
                ];
            });

            return response()->json([
                'data' => $data,
                'message' => 'Data pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in getPelatihanDetails:', ['error' => $e->getMessage()]);
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data pelatihan',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function getEventDetail($id)
    {
        try {
            // Ambil ID user yang sedang login
            $userId = auth('api')->id();

            // Ambil data agenda pelatihan berdasarkan ID
            $agenda = AgendaPelatihan::with('pelatihan')
                ->where('id_agenda', $id)
                ->firstOrFail();

            // Uraikan (decode) string JSON menjadi array
            $mentorIds = json_decode($agenda->id_mentor, true);

            // Pastikan bahwa hasil decode adalah array dan tidak null
            if (!is_array($mentorIds)) {
                throw new \Exception("Format data id_mentor tidak valid");
            }

            // Ambil data mentor yang sesuai dengan ID yang ada dalam array
            $mentors = Mentor::whereIn('id_mentor', $mentorIds)
                ->where('is_deleted', false)
                ->get();

            // Cek apakah user sudah terdaftar di event
            $isRegistered = PendaftaranEvent::where('id_peserta', $userId)
                ->where('id_agenda', $agenda->id_agenda)
                ->exists();

            // Siapkan data response
            $data = [
                'id_agenda' => $agenda->id_agenda,
                'namaPelatihan' => $agenda->pelatihan->nama_pelatihan,
                'image' => $agenda->poster_agenda,
                'deskripsi' => $agenda->pelatihan->deskripsi,
                'benefit' => json_decode($agenda->pelatihan->benefit),
                'materi' => json_decode($agenda->pelatihan->materi),
                'start_date' => $agenda->start_date,
                'end_date' => $agenda->end_date,
                'mentor' => $mentors->map(function ($mentor) {
                    return [
                        'id_mentor' => $mentor->id_mentor,
                        'nama_mentor' => $mentor->nama_mentor,
                        'email' => $mentor->email,
                        'no_kontak' => $mentor->no_kontak,
                        'aktivitas' => $mentor->aktivitas
                    ];
                }),
                'investasi' => $agenda->investasi,
                'investasi_info' => json_decode($agenda->investasi_info),
                'discount' => $agenda->diskon,
                'is_registered' => $isRegistered // Tambahkan status pendaftaran user
            ];

            return response()->json([
                'data' => $data,
                'message' => 'Detail event berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan detail event',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getProduk(Request $request)
    {
        try {

            // Ambil semua data agenda pelatihan
            $agendas = AgendaPelatihan::with('pelatihan')
                ->select('id_agenda', 'id_pelatihan', 'batch', 'poster_agenda', 'is_deleted')
                ->orderBy('start_date', 'desc')
                ->get();



            $data = $agendas->map(function ($agenda) {
                return [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    // 'gambar_pelatihan' => $agenda->pelatihan->gambar_pelatihan,
                    'batch' => $agenda->batch,
                    'poster_agenda' => $agenda->poster_agenda,
                    'is_deleted' => $agenda->is_deleted,
                ];
            });

            return response()->json([
                'data' => $data,
                'message' => 'Data produk berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in getProduk:', ['error' => $e->getMessage()]);
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan data produk',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
