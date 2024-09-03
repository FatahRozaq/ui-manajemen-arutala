<?php

namespace App\Http\Controllers\Api;

use App\Models\Mentor;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ApiLamanPesertaController extends Controller
{
    public function getPelatihanDetails()
    {
        try {
            // Ambil semua agenda dengan status "Masa Pendaftaran"
            $agendas = AgendaPelatihan::with('pelatihan')
                ->where('status', 'Masa Pendaftaran')
                ->orderBy('start_date', 'desc')
                ->get();

            // Siapkan data response
            $data = $agendas->map(function ($agenda) {
                return [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $agenda->pelatihan->gambar_pelatihan,
                    'deskripsi' => $agenda->pelatihan->deskripsi,
                    'batch' => $agenda->batch,
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'start_date' => $agenda->start_date,
                    'is_deleted' => $agenda->is_deleted,
                ];
            });

            // Return response dengan data pelatihan
            return response()->json([
                'data' => $data,
                'message' => 'Data pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
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

            // Siapkan data response
            $data = [
                'id_agenda' => $agenda->id_agenda,
                'namaPelatihan' => $agenda->pelatihan->nama_pelatihan,
                'image' => $agenda->pelatihan->gambar_pelatihan,
                'deskripsi' => $agenda->pelatihan->deskripsi,
                'benefit' => json_decode($agenda->pelatihan->benefit),
                'materi' => json_decode($agenda->pelatihan->materi),
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
}
