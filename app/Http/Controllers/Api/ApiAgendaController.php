<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use App\Models\Pelatihan;
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
                'investasi_info' => 'required|array',
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
            // Validasi request (hanya validasi field yang ada di request)
            $request->validate([
                'nama_pelatihan' => 'string|max:255',
                'start_date' => 'date',
                'end_date' => 'date',
                'sesi' => 'array',
                'investasi' => 'integer',
                'investasi_info' => 'array',
                'diskon' => 'nullable|integer',
                'status' => 'string|max:255',
                'start_pendaftaran' => 'date',
                'end_pendaftaran' => 'date',
                'link_mayar' => 'string|max:255',
                'id_mentor' => 'array'
            ]);

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

            DB::commit();

            // Return response dengan data yang telah diupdate
            return response()->json([
                'data' => [
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
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengupdate agenda pelatihan',
                'statusCode' => 500,
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailAgenda($id)
    {
        try {
            // Retrieve the agenda with its mentors
            $agenda = AgendaPelatihan::findOrFail($id);

            // Get related mentors
            $mentors = $agenda->mentors();

            // Return response with agenda and mentor details
            return response()->json([
                'data' => [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'batch' => $agenda->batch,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
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
