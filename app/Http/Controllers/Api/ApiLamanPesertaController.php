<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;

class ApiLamanPesertaController extends Controller
{
    public function getPelatihanDetails()
    {
        try {
            // Ambil semua agenda dengan status "Masa Pendaftaran"
            $agendas = AgendaPelatihan::with('pelatihan')
                ->where('status', 'Masa Pendaftaran')
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
}
