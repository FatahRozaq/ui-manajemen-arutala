<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ApiMasterPelatihanController extends Controller
{
    public function index()
    {
        try {
            // Ambil pelatihan beserta gambar dan agenda terkait
            $pelatihan = Pelatihan::with('agendaPelatihan')
                ->get(['id_pelatihan', 'nama_pelatihan', 'gambar_pelatihan']);

            // Siapkan data response
            $responseData = $pelatihan->map(function ($item) {
                return [
                    'id_pelatihan' => $item->id_pelatihan,
                    'nama_pelatihan' => $item->nama_pelatihan,
                    'gambar_pelatihan' => $item->gambar_pelatihan,
                    'jumlah_batch' => $item->agendaPelatihan->count(), // Hitung jumlah batch
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
                'gambar_pelatihan' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'deskripsi' => 'required|string|max:255',
                'materi' => 'required|array',
                'benefit' => 'required|array'
            ], [
                'nama_pelatihan.unique' => 'Nama pelatihan sudah ada, silakan gunakan nama lain.',
            ]);

            // Simpan file gambar dan ambil nama file
            if ($request->hasFile('gambar_pelatihan')) {
                $fileName = time() . '.' . $request->gambar_pelatihan->extension();
                $request->gambar_pelatihan->move(public_path('uploads'), $fileName);
            } else {
                return response()->json([
                    'message' => 'Gambar pelatihan wajib diunggah',
                    'statusCode' => 400,
                    'status' => 'error'
                ], 400);
            }

            // Simpan data pelatihan
            $pelatihan = new Pelatihan();
            $pelatihan->nama_pelatihan = $request->input('nama_pelatihan');
            $pelatihan->gambar_pelatihan = $fileName; // Simpan nama file ke database
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


    public function show($id)
    {
        try {
            // Cari pelatihan berdasarkan ID
            $pelatihan = Pelatihan::findOrFail($id);

            return response()->json([
                'data' => [
                    'id_pelatihan' => $pelatihan->id_pelatihan,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'materi' => json_decode($pelatihan->materi),
                    'benefit' => json_decode($pelatihan->benefit),
                ],
                'message' => 'Detail pelatihan berhasil diambil',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal mengambil detail pelatihan',
                'statusCode' => 500,
                'status' => 'error'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Temukan pelatihan berdasarkan ID
            $pelatihan = Pelatihan::findOrFail($id);

            // Validasi request
            $request->validate([
                'nama_pelatihan' => 'nullable|string|max:255|unique:pelatihan,nama_pelatihan,' . $id . ',id_pelatihan',
                'gambar_pelatihan' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'deskripsi' => 'nullable|string|max:255',
                'materi' => 'nullable|array',
                'benefit' => 'nullable|array'
            ], [
                'nama_pelatihan.unique' => 'Nama pelatihan sudah ada, silakan gunakan nama lain.',
            ]);

            // Handle file upload jika ada file baru yang diunggah
            if ($request->hasFile('gambar_pelatihan')) {
                $fileName = time() . '.' . $request->gambar_pelatihan->extension();
                $request->gambar_pelatihan->move(public_path('uploads'), $fileName);

                // Hapus gambar lama jika ada
                if ($pelatihan->gambar_pelatihan) {
                    Storage::delete('uploads/' . $pelatihan->gambar_pelatihan);
                }

                $pelatihan->gambar_pelatihan = $fileName;
            }

            // Update data pelatihan dengan data baru atau tetap menggunakan nilai lama jika null
            $pelatihan->nama_pelatihan = $request->input('nama_pelatihan', $pelatihan->nama_pelatihan);
            $pelatihan->deskripsi = $request->input('deskripsi', $pelatihan->deskripsi);
            $pelatihan->materi = $request->has('materi') ? json_encode($request->input('materi')) : $pelatihan->materi;
            $pelatihan->benefit = $request->has('benefit') ? json_encode($request->input('benefit')) : $pelatihan->benefit;
            $pelatihan->modified_time = now();


            $pelatihan->save();




            // Return response dengan data yang baru diperbarui
            return response()->json([
                'message' => 'Pelatihan berhasil diupdate',
                'statusCode' => 200,
                'status' => 'success',
                'data' => [
                    'id_pelatihan' => $pelatihan->id_pelatihan,
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
                    'deskripsi' => $pelatihan->deskripsi,
                    'materi' => json_decode($pelatihan->materi),
                    'benefit' => json_decode($pelatihan->benefit),
                    'modified_time' => $pelatihan->modified_time
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
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


    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     try {
    //         // Cari pelatihan berdasarkan ID
    //         $pelatihan = Pelatihan::findOrFail($id);

    //         // Update data pelatihan hanya jika field diberikan dalam request
    //         if ($request->filled('nama_pelatihan')) {
    //             $pelatihan->nama_pelatihan = $request->input('nama_pelatihan');
    //         }

    //         if ($request->hasFile('gambar_pelatihan')) {
    //             // Hapus gambar lama jika ada
    //             if ($pelatihan->gambar_pelatihan && Storage::exists('public/pelatihan/' . $pelatihan->gambar_pelatihan)) {
    //                 Storage::delete('public/pelatihan/' . $pelatihan->gambar_pelatihan);
    //             }

    //             // Simpan gambar baru
    //             $file = $request->file('gambar_pelatihan');
    //             $filename = time() . '_' . $file->getClientOriginalName();
    //             $path = $file->storeAs('public/pelatihan', $filename);
    //             $pelatihan->gambar_pelatihan = $filename; // Simpan nama file saja
    //         }

    //         if ($request->filled('deskripsi')) {
    //             $pelatihan->deskripsi = $request->input('deskripsi');
    //         }

    //         if ($request->filled('materi')) {
    //             $pelatihan->materi = json_encode($request->input('materi'));
    //         }

    //         if ($request->filled('benefit')) {
    //             $pelatihan->benefit = json_encode($request->input('benefit'));
    //         }

    //         $pelatihan->modified_by = 'Adminu'; // Atur sesuai kebutuhan Anda
    //         $pelatihan->modified_time = now();

    //         $pelatihan->save();

    //         // Return response dengan data yang telah diperbarui
    //         return response()->json([
    //             'message' => 'Pelatihan berhasil diperbarui',
    //             'statusCode' => 200,
    //             'status' => 'success',
    //             'data' => [
    //                 'id_pelatihan' => $pelatihan->id_pelatihan,
    //                 'nama_pelatihan' => $pelatihan->nama_pelatihan,
    //                 'gambar_pelatihan' => $pelatihan->gambar_pelatihan,
    //                 'deskripsi' => $pelatihan->deskripsi,
    //                 'materi' => json_decode($pelatihan->materi),
    //                 'benefit' => json_decode($pelatihan->benefit),
    //                 'modified_by' => $pelatihan->modified_by,
    //                 'modified_time' => $pelatihan->modified_time
    //             ]
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Gagal memperbarui pelatihan',
    //             'statusCode' => 500,
    //             'status' => 'error',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}