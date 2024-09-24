<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\Validator;
use App\Models\Mentor;
use Illuminate\Support\Facades\DB;

class ApiAgendaController extends Controller
{

    public function index()
    {
        try {
            // Ambil semua agenda pelatihan yang tidak dihapus
            $agendas = AgendaPelatihan::with('pelatihan')
                ->where('is_deleted', false)
                ->get();

            // Siapkan data untuk response
            $responseData = $agendas->map(function ($agenda) {
                // Hitung jumlah peserta untuk agenda ini
                $jumlahPeserta = $agenda->pendaftaranEvent()->count();

                return [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'batch' => $agenda->batch,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'jumlah_peserta' => $jumlahPeserta,
                    'status' => $agenda->status
                ];
            });

            // Return response dengan data agenda pelatihan
            return response()->json([
                'data' => $responseData,
                'message' => 'Daftar agenda pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal mengambil daftar agenda pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeAgenda(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi request
            $request->validate([
                'nama_pelatihan' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'sesi' => 'required|array',
                'investasi' => 'required|integer',
                'investasi_info' => 'required|array|max:255',
                'diskon' => 'nullable|integer',
                'status' => 'required|string|max:255',
                'start_pendaftaran' => 'required|date',
                'end_pendaftaran' => 'required|date',
                'link_mayar' => 'required|string|max:255',
                'id_mentor' => 'required|array'
            ]);

            // Verifikasi bahwa pelatihan ada
            $pelatihan = Pelatihan::where('nama_pelatihan', $request->input('nama_pelatihan'))->first();

            if (!$pelatihan) {
                return response()->json([
                    'message' => 'Nama pelatihan tidak ditemukan',
                    'statusCode' => 404,
                    'status' => 'error'
                ], 404);
            }

            // Verifikasi bahwa semua mentor yang diberikan ada di tabel Mentor
            foreach ($request->input('id_mentor') as $mentor_id) {
                $mentorExists = Mentor::find($mentor_id);
                if (!$mentorExists) {
                    return response()->json([
                        'message' => "Mentor dengan ID $mentor_id tidak ditemukan",
                        'statusCode' => 404,
                        'status' => 'error'
                    ], 404);
                }
            }

            // Hitung batch berdasarkan jumlah agenda dengan id_pelatihan yang sama
            $batchCount = AgendaPelatihan::where('id_pelatihan', $pelatihan->id_pelatihan)->count();
            $batch = $batchCount + 1;

            // Simpan data agenda pelatihan
            $agenda = new AgendaPelatihan();
            $agenda->start_date = $request->input('start_date');
            $agenda->end_date = $request->input('end_date');
            $agenda->sesi = json_encode($request->input('sesi')); // Simpan sebagai JSON string
            $agenda->investasi = $request->input('investasi');
            $agenda->investasi_info = json_encode($request->input('investasi_info')); // Simpan sebagai JSON string
            $agenda->diskon = $request->input('diskon');
            $agenda->status = $request->input('status');
            $agenda->start_pendaftaran = $request->input('start_pendaftaran');
            $agenda->end_pendaftaran = $request->input('end_pendaftaran');
            $agenda->link_mayar = $request->input('link_mayar');
            $agenda->id_pelatihan = $pelatihan->id_pelatihan;
            $agenda->batch = $batch;
            $agenda->id_mentor = json_encode($request->input('id_mentor')); // Simpan sebagai JSON string

            $agenda->save();

            DB::commit();

            // Return response dengan data yang baru ditambahkan
            return response()->json([
                'data' => [
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'sesi' => json_decode($agenda->sesi),
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'start_pendaftaran' => $agenda->start_pendaftaran,
                    'end_pendaftaran' => $agenda->end_pendaftaran,
                    'link_mayar' => $agenda->link_mayar,
                    'id_mentor' => json_decode($agenda->id_mentor),
                    'is_deleted' => $agenda->is_deleted,
                ],
                'message' => 'Agenda pelatihan berhasil ditambahkan',
                'statusCode' => 201,
                'status' => 'success'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan agenda pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateAgenda(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validasi input menggunakan Validator
            $validator = Validator::make($request->all(), [
                'nama_pelatihan' => 'required|string|max:255', // Pastikan nama pelatihan ada
                'start_date' => 'required|date|after_or_equal:today', // Harus ada dan merupakan tanggal, tidak boleh sebelum hari ini
                'end_date' => 'required|date|after_or_equal:start_date', // Harus lebih besar dari atau sama dengan start_date
                'sesi' => 'required|array|min:1', // Minimal harus ada 1 sesi
                'sesi.*' => 'required|string|max:255', // Setiap sesi harus berupa string
                'investasi' => 'required|integer|min:0', // Investasi harus angka positif
                'investasi_info' => 'nullable|array|min:1', // Investasi info harus array dengan minimal 1 item
                'investasi_info.*' => 'nullable|string|max:255', // Setiap investasi info harus berupa string
                'diskon' => 'nullable|integer|min:0|max:100', // Diskon harus angka antara 0 dan 100, nullable berarti bisa kosong
                'status' => 'required|string|in:Planning,Masa Pendaftaran,Sedang Berlangsung,Selesai', // Status harus sesuai dengan pilihan yang valid
                'start_pendaftaran' => 'required|date|before_or_equal:start_date', // Start pendaftaran harus sebelum atau sama dengan start_date
                'end_pendaftaran' => 'required|date|after_or_equal:start_pendaftaran', // End pendaftaran harus setelah start_pendaftaran
                'link_mayar' => 'required|string|url|max:255', // Link pembayaran harus URL yang valid
                'id_mentor' => 'required|array|min:1', // Mentor harus minimal 1

            ], [
                // Custom error messages
                'nama_pelatihan.required' => 'Nama pelatihan wajib diisi.',
                'start_date.required' => 'Tanggal mulai wajib diisi.',
                'start_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
                'end_date.required' => 'Tanggal selesai wajib diisi.',
                'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
                'sesi.required' => 'Sesi pelatihan wajib diisi.',
                'investasi.required' => 'Investasi wajib diisi.',
                'investasi_info.required' => 'Informasi investasi wajib diisi.',
                'diskon.integer' => 'Diskon harus berupa angka antara 0 dan 100.',
                'status.in' => 'Status pelatihan tidak valid.',
                'link_mayar.url' => 'Link pembayaran harus berupa URL yang valid.',

            ]);

            // Jika validasi gagal, kembalikan response error
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'statusCode' => 422,
                    'status' => 'error'
                ], 422);
            }

            // Cari agenda berdasarkan ID
            $agenda = AgendaPelatihan::findOrFail($id);

            // Update hanya field yang ada di request
            if ($request->has('start_date')) {
                $agenda->start_date = $request->input('start_date');
            }

            if ($request->has('end_date')) {
                $agenda->end_date = $request->input('end_date');
            }

            if ($request->has('sesi')) {
                $agenda->sesi = json_encode($request->input('sesi'));
            }

            if ($request->has('investasi')) {
                $agenda->investasi = $request->input('investasi');
            }

            if ($request->has('investasi_info')) {
                $agenda->investasi_info = json_encode($request->input('investasi_info'));
            }

            if ($request->has('diskon')) {
                $agenda->diskon = $request->input('diskon');
            }

            if ($request->has('status')) {
                $agenda->status = $request->input('status');
            }

            if ($request->has('start_pendaftaran')) {
                $agenda->start_pendaftaran = $request->input('start_pendaftaran');
            }

            if ($request->has('end_pendaftaran')) {
                $agenda->end_pendaftaran = $request->input('end_pendaftaran');
            }

            if ($request->has('link_mayar')) {
                $agenda->link_mayar = $request->input('link_mayar');
            }

            if ($request->has('id_mentor')) {
                // Verifikasi bahwa semua mentor yang diberikan ada di tabel Mentor
                foreach ($request->input('id_mentor') as $mentor_id) {
                    $mentorExists = Mentor::find($mentor_id);
                    if (!$mentorExists) {
                        return response()->json([
                            'message' => "Mentor dengan ID $mentor_id tidak ditemukan",
                            'statusCode' => 404,
                            'status' => 'error'
                        ], 404);
                    }
                }
                $agenda->id_mentor = json_encode($request->input('id_mentor'));
            }

            // Simpan perubahan
            $agenda->save();

            // Komit transaksi
            DB::commit();

            // Return response dengan data yang telah diupdate
            return response()->json([
                'data' => [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'sesi' => json_decode($agenda->sesi),
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'start_pendaftaran' => $agenda->start_pendaftaran,
                    'end_pendaftaran' => $agenda->end_pendaftaran,
                    'link_mayar' => $agenda->link_mayar,
                    'id_mentor' => json_decode($agenda->id_mentor),
                ],
                'message' => 'Agenda pelatihan berhasil diupdate',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, rollback transaksi
            return response()->json([
                'message' => 'Gagal mengupdate agenda pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function getPelatihanMentorData()
    {
        try {
            // Ambil data pelatihan dan mentor dari database
            $pelatihans = Pelatihan::where('is_deleted', false)->get(['nama_pelatihan']);
            $mentors = Mentor::all(['id_mentor', 'nama_mentor']);

            // Kirim data dalam format JSON
            return response()->json([
                'pelatihans' => $pelatihans,
                'mentors' => $mentors
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data pelatihan dan mentor',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function detailAgenda($id)
    {
        try {
            $agenda = AgendaPelatihan::with('pelatihan')->findOrFail($id);

            // Ambil ID mentor dari kolom JSON
            $mentorIds = json_decode($agenda->id_mentor);

            // Ambil detail mentor berdasarkan ID
            $mentors = Mentor::whereIn('id_mentor', $mentorIds)->get(['id_mentor', 'nama_mentor']);

            return response()->json([
                'data' => [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'batch' => $agenda->batch,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'start_pendaftaran' => $agenda->start_pendaftaran,
                    'end_pendaftaran' => $agenda->end_pendaftaran,
                    'sesi' => json_decode($agenda->sesi),
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'link_mayar' => $agenda->link_mayar,
                    'mentors' => $mentors,
                    'is_deleted' => $agenda->is_deleted
                ],
                'message' => 'Detail agenda pelatihan berhasil ditemukan',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => 'Gagal menemukan detail agenda pelatihan',
                'statusCode' => 404,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 404);
        }
    }



    public function deleteAgenda($id)
    {
        try {
            // Cari agenda berdasarkan ID
            $agenda = AgendaPelatihan::findOrFail($id);

            // Set is_deleted menjadi true
            $agenda->is_deleted = true;

            // Simpan perubahan
            $agenda->save();

            // Return response setelah berhasil dihapus (soft delete)
            return response()->json([
                'message' => 'Agenda pelatihan berhasil dihapus',
                'statusCode' => 200,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus agenda pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
