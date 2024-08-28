<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\AgendaPelatihan;

class ApiMasterPelatihanController extends Controller
{
    public function index()
    {
        try {
            $pelatihan = Pelatihan::with(['agendaPelatihan' => function ($query) {
                $query->select('id_agenda', 'id_pelatihan', 'batch', 'start_date', 'status');
            }])->get(['id_pelatihan', 'nama_pelatihan']);

            $responseData = $pelatihan->map(function ($item) {
                return [
                    'id_pelatihan' => $item->id_pelatihan,
                    'nama_pelatihan' => $item->nama_pelatihan,
                    'agenda_pelatihan' => $item->agendaPelatihan->map(function ($agenda) {
                        return [
                            'id_agenda' => $agenda->id_agenda,
                            'batch' => $agenda->batch,
                            'start_date' => $agenda->start_date,
                            'status' => $agenda->status
                        ];
                    })
                ];
            });

            return response()->json([
                'data' => $responseData,
                'message' => 'Data pelatihan berhasil diambil',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal mengambil data pelatihan',
                'statusCode' => 500,
                'status' => 'error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'nama_pelatihan' => 'required|string|max:255|unique:pelatihan,nama_pelatihan',
                'gambar_pelatihan' => 'required|string|max:255',
                'deskripsi' => 'required|string|max:255',
                'materi' => 'required|array',
                'benefit' => 'required|array'
            ], [
                'nama_pelatihan.unique' => 'Nama pelatihan sudah ada, silakan gunakan nama lain.',
            ]);

            // Simpan data pelatihan
            $pelatihan = new Pelatihan();
            $pelatihan->nama_pelatihan = $request->input('nama_pelatihan');
            $pelatihan->gambar_pelatihan = $request->input('gambar_pelatihan');
            $pelatihan->deskripsi = $request->input('deskripsi');
            $pelatihan->materi = json_encode($request->input('materi')); // Simpan sebagai JSON string
            $pelatihan->benefit = json_encode($request->input('benefit')); // Simpan sebagai JSON string
            $pelatihan->is_deleted = false;
            $pelatihan->created_by = 'Admin'; // Atur sesuai kebutuhan Anda
            $pelatihan->created_time = now();

            $pelatihan->save();

            // Return response dengan data yang baru ditambahkan
            return response()->json([
                'message' => 'Pelatihan berhasil ditambahkan',
                'statusCode' => 201,
                'status' => 'success',
                'data' => [
                    'id_pelatihan' => $pelatihan->id_pelatihan,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'materi' => json_decode($pelatihan->materi),
                    'benefit' => json_decode($pelatihan->benefit),
                    'is_deleted' => $pelatihan->is_deleted,
                    'created_by' => $pelatihan->created_by,
                    'created_time' => $pelatihan->created_time
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Cari pelatihan berdasarkan ID
            $pelatihan = Pelatihan::findOrFail($id);

            // Update data pelatihan hanya jika field diberikan dalam request
            if ($request->has('nama_pelatihan')) {
                $pelatihan->nama_pelatihan = $request->input('nama_pelatihan');
            }

            if ($request->has('gambar_pelatihan')) {
                $pelatihan->gambar_pelatihan = $request->input('gambar_pelatihan');
            }

            if ($request->has('deskripsi')) {
                $pelatihan->deskripsi = $request->input('deskripsi');
            }

            if ($request->has('materi')) {
                $pelatihan->materi = json_encode($request->input('materi'));
            }

            if ($request->has('benefit')) {
                $pelatihan->benefit = json_encode($request->input('benefit'));
            }

            $pelatihan->modified_by = 'Admin'; // Atur sesuai kebutuhan Anda
            $pelatihan->modified_time = now();

            $pelatihan->save();

            // Return response dengan data yang telah diperbarui
            return response()->json([
                'message' => 'Pelatihan berhasil diperbarui',
                'statusCode' => 200,
                'status' => 'success',
                'data' => [
                    'id_pelatihan' => $pelatihan->id_pelatihan,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'materi' => json_decode($pelatihan->materi),
                    'benefit' => json_decode($pelatihan->benefit),
                    'modified_by' => $pelatihan->modified_by,
                    'modified_time' => $pelatihan->modified_time
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Cari pelatihan berdasarkan ID
            $pelatihan = Pelatihan::findOrFail($id);

            // Return response dengan data pelatihan yang ditemukan
            return response()->json([
                'data' => [
                    'id_pelatihan' => $pelatihan->id_pelatihan,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'materi' => json_decode($pelatihan->materi),
                    'benefit' => json_decode($pelatihan->benefit),
                    'is_deleted' => $pelatihan->is_deleted,
                ],
                'message' => 'Detail pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan detail pelatihan',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Cari pelatihan berdasarkan ID
            $pelatihan = Pelatihan::findOrFail($id);

            // Set is_deleted menjadi true
            $pelatihan->is_deleted = true;
            $pelatihan->modified_by = 'Admin'; // Atur sesuai kebutuhan Anda
            $pelatihan->modified_time = now();

            // Simpan perubahan
            $pelatihan->save();

            // Return response setelah berhasil dihapus (soft delete)
            return response()->json([
                'message' => 'Pelatihan berhasil ditandai sebagai dihapus',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menandai pelatihan sebagai dihapus',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
