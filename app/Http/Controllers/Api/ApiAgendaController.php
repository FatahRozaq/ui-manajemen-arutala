<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgendaPelatihan;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Mentor;
use Illuminate\Support\Facades\DB;

class ApiAgendaController extends Controller
{

    public function index()
    {
        try {
            // Mendapatkan waktu sekarang
            $now = now();

            // Ambil semua agenda pelatihan yang tidak dihapus
            $agendas = AgendaPelatihan::with('pelatihan')
                ->where('is_deleted', false)
                ->get();

            // Update status agenda pelatihan berdasarkan waktu sekarang
            $agendas->each(function ($agenda) use ($now) {
                if ($now->lessThan($agenda->start_pendaftaran)) {
                    // Sebelum start_pendaftaran -> Planning
                    $agenda->update(['status' => 'Planning']);
                } elseif ($now->between($agenda->start_pendaftaran, $agenda->end_pendaftaran)) {
                    // Setelah start_pendaftaran dan sebelum end_pendaftaran -> Masa Pendaftaran
                    $agenda->update(['status' => 'Masa Pendaftaran']);
                } elseif ($now->greaterThan($agenda->end_pendaftaran) && $now->lessThan($agenda->start_date)) {
                    // Setelah end_pendaftaran dan sebelum start_date -> Pendaftaran Berakhir
                    $agenda->update(['status' => 'Pendaftaran Berakhir']);
                } elseif ($now->between($agenda->start_date, $agenda->end_date)) {
                    // Setelah start_date dan sebelum end_date -> Sedang Berlangsung
                    $agenda->update(['status' => 'Sedang Berlangsung']);
                } elseif ($now->greaterThan($agenda->end_date)) {
                    // Setelah end_date -> Selesai
                    $agenda->update(['status' => 'Selesai']);
                }
            });

            // Siapkan data untuk response
            $responseData = $agendas->map(function ($agenda) {
                // Hitung jumlah peserta untuk agenda ini
                $jumlahPeserta = $agenda->pendaftaranEvent()->count();

                return [
                    'id_agenda' => $agenda->id_agenda,
                    'nama_pelatihan' => $agenda->pelatihan->nama_pelatihan,
                    'batch' => $agenda->batch,
                    'poster_agenda' => $agenda->poster_agenda,
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
                'sesi' => 'required|string|max:255',
                'investasi' => 'required|integer',
                'investasi_info' => 'nullable|max:255',
                'diskon' => 'nullable|integer',
                'status' => 'required|string|max:255',
                'start_pendaftaran' => 'required|date',
                'end_pendaftaran' => 'required|date',
                'link_mayar' => 'required|string|max:255',
                'id_mentor' => 'required|array',
                'poster_agenda' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
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
            $agenda->sesi = $request->input('sesi');
            $agenda->investasi = $request->input('investasi');

            // Jika investasi_info kosong atau null, simpan null, jika ada, simpan sebagai JSON
            if ($request->has('investasi_info') && !empty($request->input('investasi_info'))) {
                $agenda->investasi_info = json_encode($request->input('investasi_info'));
            } else {
                $agenda->investasi_info = null;
            }

            $agenda->diskon = $request->input('diskon');
            $agenda->status = $request->input('status');
            $agenda->start_pendaftaran = $request->input('start_pendaftaran');
            $agenda->end_pendaftaran = $request->input('end_pendaftaran');
            $agenda->link_mayar = $request->input('link_mayar');
            $agenda->id_pelatihan = $pelatihan->id_pelatihan;
            $agenda->batch = $batch;
            $agenda->id_mentor = json_encode($request->input('id_mentor'));

            if ($request->hasFile('poster_agenda')) {
                // Ambil nama pelatihan dan batch dari request atau variabel yang sudah ada
                $namaPelatihan = str_replace(' ', '_', $pelatihan->nama_pelatihan); // Ganti spasi dengan underscore
                $batch = $batch; // Batch dihitung sebelumnya

                // Buat nama file dengan format yang diinginkan: NamaPelatihan_batch_TimeStamp.extension
                $fileName = $namaPelatihan . '_batch_' . $batch . '_' . time() . '.' . $request->poster_agenda->extension();

                // Buat folder path berdasarkan nama pelatihan saja
                $folderPath = 'uploads/poster_agenda/' . $namaPelatihan;

                // Gabungkan folderPath dan fileName untuk mendapatkan full path
                $filePath = $folderPath . '/' . $fileName;

                // Simpan file ke MinIO
                Storage::disk('minio')->put($filePath, file_get_contents($request->file('poster_agenda')));

                // Buat URL gambar untuk disimpan di database
                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;

                // Simpan URL gambar ke database
                $agenda->poster_agenda = $gambarUrl;
            } else {
                // Jika tidak ada poster di-upload, simpan null
                $agenda->poster_agenda = null;
            }




            $agenda->save();

            DB::commit();

            // Return response dengan data yang baru ditambahkan
            return response()->json([
                'data' => [
                    'nama_pelatihan' => $pelatihan->nama_pelatihan,
                    'start_date' => $agenda->start_date,
                    'end_date' => $agenda->end_date,
                    'sesi' => $agenda->sesi,
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'start_pendaftaran' => $agenda->start_pendaftaran,
                    'end_pendaftaran' => $agenda->end_pendaftaran,
                    'link_mayar' => $agenda->link_mayar,
                    'id_mentor' => json_decode($agenda->id_mentor),
                    'poster_agenda' => $agenda->poster_agenda, // Tambahkan poster_agenda ke dalam response
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
                'nama_pelatihan' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'sesi' => 'required|string|max:255',
                'investasi' => 'required|integer|min:0',
                'investasi_info' => 'nullable|array|min:1',
                'investasi_info.*' => 'nullable|string|max:255',
                'diskon' => 'nullable|integer|min:0|max:100',
                'status' => 'required|string|in:Planning,Masa Pendaftaran,Sedang Berlangsung,Selesai,Pendaftaran Berakhir',
                'start_pendaftaran' => 'required|date|before_or_equal:start_date',
                'end_pendaftaran' => 'required|date|after_or_equal:start_pendaftaran|before_or_equal:end_date',
                'link_mayar' => 'required|string|max:255',
                'id_mentor' => 'required|array|min:1',
                'poster_agenda' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ], [
                // Custom error messages
                'nama_pelatihan.required' => 'Nama pelatihan wajib diisi.',
                'start_date.required' => 'Tanggal mulai wajib diisi.',
                'end_date.required' => 'Tanggal selesai wajib diisi.',
                'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
                'sesi.required' => 'Sesi pelatihan wajib diisi.',
                'sesi.string' => 'Sesi pelatihan harus berupa teks.',
                'investasi.required' => 'Investasi wajib diisi.',
                'investasi.integer' => 'Investasi harus berupa angka.',
                'investasi.min' => 'Investasi harus bernilai positif.',
                'investasi_info.array' => 'Informasi investasi harus berupa array.',
                'investasi_info.min' => 'Setidaknya satu informasi investasi harus diisi.',
                'investasi_info.*.string' => 'Setiap informasi investasi harus berupa teks.',
                'diskon.integer' => 'Diskon harus berupa angka antara 0 dan 100.',
                'diskon.min' => 'Diskon minimal harus bernilai 0%.',
                'diskon.max' => 'Diskon maksimal hanya sampai 100%.',
                'status.required' => 'Status pelatihan wajib dipilih.',
                'status.in' => 'Status pelatihan tidak valid.',
                'start_pendaftaran.required' => 'Tanggal mulai pendaftaran wajib diisi.',
                'start_pendaftaran.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai pelatihan.',
                'end_pendaftaran.required' => 'Tanggal akhir pendaftaran wajib diisi.',
                'end_pendaftaran.after_or_equal' => 'Tanggal akhir pendaftaran harus setelah atau sama dengan tanggal mulai pendaftaran.',
                'end_pendaftaran.before_or_equal' => 'Tanggal akhir pendaftaran tidak boleh setelah akhir pelatihan.',
                'link_mayar.required' => 'Link pembayaran wajib diisi.',
                'link_mayar.max' => 'Link pembayaran tidak boleh melebihi 255 karakter.',
                'id_mentor.required' => 'Setidaknya satu mentor harus dipilih.',
                'id_mentor.array' => 'Mentor harus berupa array.',
                'poster_agenda.image' => 'Poster harus berupa file gambar.',
                'poster_agenda.mimes' => 'Poster hanya boleh dalam format jpeg, png, jpg, gif, atau svg.',
                'poster_agenda.max' => 'Ukuran file poster tidak boleh melebihi 5 MB.'
            ]);

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
                $agenda->sesi = $request->input('sesi'); // Update sebagai string, bukan array
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

            if ($request->hasFile('poster_agenda')) {
                // Hapus gambar lama dari storage MinIO jika ada
                if ($agenda->poster_agenda) {
                    $oldFilePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $agenda->poster_agenda);
                    Storage::disk('minio')->delete($oldFilePath);  // Hapus file lama dari MinIO
                }

                $namaPelatihan = str_replace(' ', '_', $agenda->pelatihan->nama_pelatihan); // Ganti spasi dengan underscore
                $batch = $agenda->batch; // Batch dari agenda
                $fileName = $namaPelatihan . '_batch_' . $batch . '_' . time() . '.' . $request->poster_agenda->extension();
                $folderPath = 'uploads/poster_agenda/' . $namaPelatihan;
                $filePath = $folderPath . '/' . $fileName;

                Storage::disk('minio')->put($filePath, file_get_contents($request->file('poster_agenda')));

                // Perbarui URL poster di database
                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;
                $agenda->poster_agenda = $gambarUrl;
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
                    'sesi' => $agenda->sesi, // Tidak perlu decode JSON
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'start_pendaftaran' => $agenda->start_pendaftaran,
                    'end_pendaftaran' => $agenda->end_pendaftaran,
                    'link_mayar' => $agenda->link_mayar,
                    'poster_agenda' => $agenda->poster_agenda,
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
                    'sesi' => $agenda->sesi,
                    'investasi' => $agenda->investasi,
                    'investasi_info' => json_decode($agenda->investasi_info),
                    'diskon' => $agenda->diskon,
                    'status' => $agenda->status,
                    'link_mayar' => $agenda->link_mayar,
                    'poster_agenda' => $agenda->poster_agenda,
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
