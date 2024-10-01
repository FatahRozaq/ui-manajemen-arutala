<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PendaftaranEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ApiSertifikatController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => 'required|exists:pendaftaran_event,id_pendaftaran',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validasi file maksimal 2MB
        ], [
            'id_pendaftaran.required' => 'ID Pendaftaran wajib diisi.',
            'id_pendaftaran.exists' => 'ID Pendaftaran tidak valid atau tidak ditemukan.',
            'file_sertifikat.required' => 'File sertifikat wajib diupload.',
            'file_sertifikat.file' => 'File sertifikat harus berupa file yang valid.',
            'file_sertifikat.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $idPendaftaran = $request['id_pendaftaran'];

        $pendaftaranEvent = PendaftaranEvent::find($idPendaftaran);

        if (!$pendaftaranEvent) {
            return response()->json([
                'message' => 'Pendaftaran tidak ditemukan',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'status' => 'error',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Cek apakah sertifikat dengan id_pendaftaran sudah ada
            $sertifikat = Sertifikat::where('id_pendaftaran', $idPendaftaran)->first();
            $numberPrefix = '001'; // Default nomor prefix jika file belum ada

            if ($request->hasFile('file_sertifikat')) {
                if ($sertifikat && $sertifikat->file_sertifikat) {
                    // Jika sertifikat sudah ada, ambil nomor dari file lama
                    $existingFileName = basename($sertifikat->file_sertifikat); // Ambil nama file dari URL sertifikat
                    $existingNumber = explode('_', $existingFileName)[0]; // Ambil nomor dari format 001_namafile
                    $numberPrefix = $existingNumber;

                    // Hapus file lama dari Minio
                    $oldFilePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $sertifikat->file_sertifikat);
                    if (Storage::disk('minio')->exists($oldFilePath)) {
                        Storage::disk('minio')->delete($oldFilePath);
                    }
                } else {
                    // Jika sertifikat belum ada, cek nomor terakhir di folder
                    $pelatihanName = Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan);
                    $batch = 'batch' . $pendaftaranEvent->agendaPelatihan->batch;
                    $path = 'sertifikat/' . $pelatihanName . '/' . $batch;

                    $filesInFolder = Storage::disk('minio')->files($path);
                    if (count($filesInFolder) > 0) {
                        // Ambil nomor tertinggi dari file yang sudah ada
                        $lastFile = basename(end($filesInFolder));
                        $lastNumber = explode('_', $lastFile)[0];
                        $numberPrefix = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
                    }
                }

                // Dapatkan nama file asli
                $originalFileName = $request->file('file_sertifikat')->getClientOriginalName();

                // Gabungkan nomor prefix dengan nama file
                $fileName = $numberPrefix . '_' . $originalFileName;

                // Buat file path
                $pelatihanName = Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan);
                $batch = 'Batch ' . $pendaftaranEvent->agendaPelatihan->batch;
                $filePath = 'sertifikat/' . $pelatihanName . '/' . $batch . '/' . $fileName;

                // Upload file ke Minio
                Storage::disk('minio')->put($filePath, file_get_contents($request->file('file_sertifikat')));

                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;
            } else {
                $gambarUrl = null;
            }

            if ($sertifikat) {
                // Update data sertifikat
                $sertifikat->file_sertifikat = $gambarUrl ?: $sertifikat->file_sertifikat;
                $sertifikat->modified_by = 'Admin';
                $sertifikat->modified_time = Carbon::now();
                $sertifikat->save();

                return response()->json([
                    'message' => 'Sertifikat berhasil diperbarui',
                    'statusCode' => Response::HTTP_OK,
                    'status' => 'success',
                    'data' => $sertifikat
                ], Response::HTTP_OK);
            } else {
                // Buat sertifikat baru
                $sertifikat = Sertifikat::create([
                    'id_pendaftaran' => $idPendaftaran,
                    'id_pendaftar' => $pendaftaranEvent->id_peserta,
                    'file_sertifikat' => $gambarUrl,
                    'created_by' => 'Admin',
                    'created_time' => Carbon::now(),
                    'is_deleted' => false,
                ]);

                return response()->json([
                    'message' => 'Sertifikat berhasil diupload',
                    'statusCode' => Response::HTTP_OK,
                    'status' => 'success',
                    'data' => $sertifikat
                ], Response::HTTP_OK);
            }

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupload sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    public function update(Request $request, $idSertifikat)
    {
        $validator = Validator::make($request->all(), [
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validasi file (PDF, JPG, JPEG, PNG) maksimal 2MB
        ], [
            'file_sertifikat.file' => 'File sertifikat harus berupa file yang valid.',
            'file_sertifikat.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $sertifikat = Sertifikat::find($idSertifikat);

            if (!$sertifikat) {
                return response()->json([
                    'message' => 'Sertifikat tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($request->hasFile('file_sertifikat')) {
                $fileName = $request->file('file_sertifikat')->getClientOriginalName();
                $filePath = 'uploads/sertifikat/' . $fileName;

                // Simpan file ke minio
                Storage::disk('minio')->put($filePath, file_get_contents($request->file('file_sertifikat')));

                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;
                $sertifikat->file_sertifikat = $gambarUrl;
            }

            $sertifikat->modified_by = 'Admin';
            $sertifikat->modified_time = Carbon::now();
            $sertifikat->save();

            return response()->json([
                'message' => 'Sertifikat berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success',
                'data' => $sertifikat
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function download(Request $request)
    {
        $idPendaftaran = $request['id_pendaftaran'];
        try {
            $sertifikat = Sertifikat::where('id_pendaftaran', $idPendaftaran)->first();

            if (!$sertifikat || $sertifikat->is_deleted) {
                return response()->json([
                    'message' => 'Sertifikat tidak ditemukan atau telah dihapus',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }

            $filePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $sertifikat->file_sertifikat);

            if (Storage::disk('minio')->exists($filePath)) {
                return Storage::disk('minio')->download($filePath);
            } else {
                return response()->json([
                    'message' => 'File sertifikat tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengunduh sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function view($id)
    {
        $sertifikat = Sertifikat::where('id_pendaftaran', $id)->first();

        if (!$sertifikat || $sertifikat->is_deleted) {
            return response()->json([
                'message' => 'Sertifikat tidak ditemukan',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'status' => 'error',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Sertifikat ditemukan',
            'statusCode' => Response::HTTP_OK,
            'status' => 'success',
            'data' => [
                'file_url' => $sertifikat->file_sertifikat,
                'nama_peserta' => $sertifikat->pendaftar->nama_peserta, // Assuming relation to Pendaftar model
            ]
        ], Response::HTTP_OK);
    }

    public function sertifikatPendaftar()
    {
        try {
            $idUser = auth('api')->id();

            $sertifikat = Sertifikat::with(['pendaftaran.agendaPelatihan.pelatihan'])
                ->whereHas('pendaftaran', function($query) use ($idUser) {
                    $query->where('id_peserta', $idUser);
                })
                ->where('is_deleted', false)
                ->get();

            return response()->json([
                'data' => $sertifikat,
                'message' => 'Daftar sertifikat berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil daftar sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
