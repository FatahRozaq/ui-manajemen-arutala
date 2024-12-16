<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Pelatihan;
use App\Models\Sertifikat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AgendaPelatihan;
use App\Models\PendaftaranEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ApiSertifikatController extends Controller
{
    public function uploadKompetensi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => 'required|exists:pendaftaran_event,id_pendaftaran',
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'id_pendaftaran.required' => 'ID Pendaftaran wajib diisi.',
            'id_pendaftaran.exists' => 'ID Pendaftaran tidak valid atau tidak ditemukan.',
            'file_sertifikat.required' => 'File sertifikat wajib diupload.',
            'file_sertifikat.file' => 'File sertifikat harus berupa file yang valid.',
            'file_sertifikat.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sertifikat.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

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
            $sertifikat = Sertifikat::where('id_pendaftaran', $idPendaftaran)->first();
            $numberPrefix = '001';

            if ($request->hasFile('file_sertifikat')) {
                if ($sertifikat && $sertifikat->file_sertifikat) {
                    // Get number from old file
                    $existingFileName = basename($sertifikat->file_sertifikat);
                    $existingNumber = explode('_', $existingFileName)[0];
                    $numberPrefix = $existingNumber;

                    // Delete old file
                    $oldFilePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $sertifikat->file_sertifikat);
                    if (Storage::disk('minio')->exists($oldFilePath)) {
                        Storage::disk('minio')->delete($oldFilePath);
                    }
                } else {
                    // Get last file number
                    $pelatihanName = Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan);
                    $batch = 'batch-' . $pendaftaranEvent->agendaPelatihan->batch;
                    $path = 'sertifikat/' . $pelatihanName . '/' . $batch . '/sertifikat-kompetensi';

                    $filesInFolder = Storage::disk('minio')->files($path);
                    if (count($filesInFolder) > 0) {
                        $lastFile = basename(end($filesInFolder));
                        $lastNumber = explode('_', $lastFile)[0];
                        $numberPrefix = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
                    }
                }

                $originalFileName = $request->file('file_sertifikat')->getClientOriginalName();
                $fileName = $numberPrefix . '_' . $originalFileName;
                $filePath = 'sertifikat/' . Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan) . '/batch-' . $pendaftaranEvent->agendaPelatihan->batch . '/sertifikat-kompetensi/' . $fileName;
                Storage::disk('minio')->put($filePath, file_get_contents($request->file('file_sertifikat')));
                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;
            } else {
                $gambarUrl = null;
            }

            if ($sertifikat) {
                // Update sertifikat
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
                // Create new sertifikat
                Sertifikat::create([
                    'id_pendaftaran' => $idPendaftaran,
                    'id_pendaftar' => $pendaftaranEvent->id_peserta,
                    'file_sertifikat' => $gambarUrl,
                    'created_by' => 'Admin',
                    'created_time' => Carbon::now(),
                    'is_deleted' => false,
                ]);
            }

            return response()->json([
                'message' => 'Sertifikat berhasil diupload',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success',
                'data' => $sertifikat
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupload sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function uploadKehadiran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => 'required|exists:pendaftaran_event,id_pendaftaran',
            'sertifikat_kehadiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'id_pendaftaran.required' => 'ID Pendaftaran wajib diisi.',
            'id_pendaftaran.exists' => 'ID Pendaftaran tidak valid atau tidak ditemukan.',
            'sertifikat_kehadiran.required' => 'File sertifikat wajib diupload.',
            'sertifikat_kehadiran.file' => 'File sertifikat harus berupa file yang valid.',
            'sertifikat_kehadiran.mimes' => 'Format file harus berupa PDF, JPG, JPEG, atau PNG.',
            'sertifikat_kehadiran.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

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
            $sertifikat = Sertifikat::where('id_pendaftaran', $idPendaftaran)->first();
            $numberPrefix = '001';

            if ($request->hasFile('sertifikat_kehadiran')) {
                if ($sertifikat && $sertifikat->sertifikat_kehadiran) {
                    $existingFileName = basename($sertifikat->sertifikat_kehadiran);
                    $existingNumber = explode('_', $existingFileName)[0];
                    $numberPrefix = $existingNumber;

                    $oldFilePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $sertifikat->sertifikat_kehadiran);
                    if (Storage::disk('minio')->exists($oldFilePath)) {
                        Storage::disk('minio')->delete($oldFilePath);
                    }
                } else {
                    $pelatihanName = Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan);
                    $batch = 'batch-' . $pendaftaranEvent->agendaPelatihan->batch;
                    $path = 'sertifikat/' . $pelatihanName . '/' . $batch . '/sertifikat-kehadiran';

                    $filesInFolder = Storage::disk('minio')->files($path);
                    if (count($filesInFolder) > 0) {
                        $lastFile = basename(end($filesInFolder));
                        $lastNumber = explode('_', $lastFile)[0];
                        $numberPrefix = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
                    }
                }

                $originalFileName = $request->file('sertifikat_kehadiran')->getClientOriginalName();
                $fileName = $numberPrefix . '_' . $originalFileName;
                $filePath = 'sertifikat/' . Str::slug($pendaftaranEvent->agendaPelatihan->pelatihan->nama_pelatihan) . '/batch-' . $pendaftaranEvent->agendaPelatihan->batch . '/sertifikat-kehadiran/' . $fileName;
                Storage::disk('minio')->put($filePath, file_get_contents($request->file('sertifikat_kehadiran')));
                $gambarUrl = env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath;
            } else {
                $gambarUrl = null;
            }

            if ($sertifikat) {
                $sertifikat->sertifikat_kehadiran = $gambarUrl ?: $sertifikat->sertifikat_kehadiran;
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
                Sertifikat::create([
                    'id_pendaftaran' => $idPendaftaran,
                    'id_pendaftar' => $pendaftaranEvent->id_peserta,
                    'sertifikat_kehadiran' => $gambarUrl,
                    'created_by' => 'Admin',
                    'created_time' => Carbon::now(),
                    'is_deleted' => false,
                ]);
            }

            return response()->json([
                'message' => 'Sertifikat Kehadiran berhasil diupload',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success',
                'data' => $sertifikat
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupload sertifikat',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function downloadKompetensi(Request $request)
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

    public function downloadKehadiran(Request $request)
    {
        $idPendaftaran = $request['id_pendaftaran'];
        try {
            $kehadiran = Sertifikat::where('id_pendaftaran', $idPendaftaran)->first();

            if (!$kehadiran || $kehadiran->is_deleted) {
                return response()->json([
                    'message' => 'Data kehadiran tidak ditemukan atau telah dihapus',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }

            $filePath = str_replace(env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/', '', $kehadiran->sertifikat_kehadiran);

            if (Storage::disk('minio')->exists($filePath)) {
                return Storage::disk('minio')->download($filePath);
            } else {
                return response()->json([
                    'message' => 'Sertifikat kehadiran tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengunduh sertifikat kehadiran',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function viewKompetensi($id)
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
                'nama_peserta' => $sertifikat->pendaftar->nama, // Assuming relation to Pendaftar model
            ]
        ], Response::HTTP_OK);
    }

    public function viewKehadiran($id)
    {
        $kehadiran = Sertifikat::where('id_pendaftaran', $id)->first();

        if (!$kehadiran || $kehadiran->is_deleted) {
            return response()->json([
                'message' => 'Data kehadiran tidak ditemukan',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'status' => 'error',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Data kehadiran ditemukan',
            'statusCode' => Response::HTTP_OK,
            'status' => 'success',
            'data' => [
                'file_url' => $kehadiran->sertifikat_kehadiran,
                'nama_peserta' => $kehadiran->pendaftar->nama,
            ]
        ], Response::HTTP_OK);
    }


    public function sertifikatPendaftar()
    {
        try {
            $idUser = auth('api')->id();

            $sertifikat = Sertifikat::with(['pendaftaran.agendaPelatihan.pelatihan'])
                ->whereHas('pendaftaran', function ($query) use ($idUser) {
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

    public function checkSertifikat($id_pendaftaran)
    {
        try {
            // Fetch the certificate based on id_pendaftaran
            $sertifikat = Sertifikat::where('id_pendaftaran', $id_pendaftaran)
                                    ->whereNotNull('file_sertifikat')
                                    ->first();

            // If certificate exists and is not null, return success response
            if ($sertifikat) {
                return response()->json([
                    'exists' => true,
                    'message' => 'Certificate found.'
                ], 200); // HTTP 200 OK
            }

            // If no certificate found, return response indicating absence
            return response()->json([
                'exists' => false,
                'message' => 'No certificate found.'
            ], 404); // HTTP 404 Not Found

        } catch (\Exception $e) {
            // Catch any exceptions and return a response with the error message
            return response()->json([
                'exists' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    public function generateQR(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelatihan' => 'required|string|max:255',
            'batch' => 'required|integer',
            'certificate_number' => [
                'required',
                'string',
                'regex:/^[^\/]+$/', // Tidak boleh mengandung garis miring
            ],
            'jenis_sertifikat' => 'required|in:kompetensi,kehadiran',
        ], [
            'nama_pelatihan.required' => 'Nama pelatihan harus diisi.',
            'nama_pelatihan.string' => 'Nama pelatihan harus berupa string.',
            'nama_pelatihan.max' => 'Nama pelatihan tidak boleh lebih dari 255 karakter.',
            
            'batch.required' => 'Batch harus diisi.',
            'batch.integer' => 'Batch harus berupa angka.',
        
            'certificate_number.required' => 'Nomor sertifikat harus diisi.',
            'certificate_number.string' => 'Nomor sertifikat harus berupa string.',
            'certificate_number.regex' => 'Nomor sertifikat tidak boleh menggunakan karakter garis miring (/).',
        
            'jenis_sertifikat.required' => 'Jenis sertifikat harus diisi.',
            'jenis_sertifikat.in' => 'Jenis sertifikat harus berupa salah satu dari: kompetensi, kehadiran.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        

        $namaPelatihan = Str::slug($request->nama_pelatihan);
        $batch = 'batch-' . $request->batch;
        $certificateNumber = $request->certificate_number;
        $jenisSertifikat = $request->jenis_sertifikat;

        try {
            $pelatihan = Pelatihan::where('nama_pelatihan', $request->input('nama_pelatihan'))->first();
            $agenda = AgendaPelatihan::where('id_pelatihan', $pelatihan->id_pelatihan)
                ->where('batch', $request->input('batch'))->first();    

            $pendaftarans = PendaftaranEvent::join('pendaftar', 'pendaftar.id_pendaftar', '=', 'pendaftaran_event.id_peserta')
                ->join('agenda_pelatihan', 'agenda_pelatihan.id_agenda', '=', 'pendaftaran_event.id_agenda')
                ->where('agenda_pelatihan.id_agenda', $agenda->id_agenda)
                ->where('pendaftar.is_deleted', false)
                ->orderBy('pendaftar.nama', 'asc') // Sorting by 'nama' of 'pendaftar'
                ->get();
            
            if ($pendaftarans->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data pendaftaran yang ditemukan',
                    'status' => 'error',
                ], Response::HTTP_NOT_FOUND);
            }

            $files = [];
            $path = "qr-codes/$namaPelatihan/$batch/$jenisSertifikat";

            foreach ($pendaftarans as $index => $pendaftaran) {
                $urutan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $url = "https://atms.arutalalab.net/sertifikat/$certificateNumber.$urutan";
                $namaPeserta = $pendaftaran->pendaftar->nama;
                $qrCodeName = "$namaPeserta.$certificateNumber.$urutan.png";

                $qrImage = QrCode::format('png')
                    ->size(300)
                    ->generate($url);

                $filePath = "$path/$qrCodeName";
                Storage::disk('minio')->put($filePath, $qrImage);

                // Simpan ke database
                Sertifikat::updateOrCreate(
                    [
                        'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                    ],
                    [
                        $jenisSertifikat === 'kompetensi' ? 'qr_kompetensi' : 'qr_kehadiran' => $url,
                        $jenisSertifikat === 'kompetensi' ? 'path_qr_kompetensi' : 'path_qr_kehadiran' => env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $filePath,
                        'certificate_number_' . $jenisSertifikat => "$certificateNumber.$urutan",
                        'modified_by' => 'Admin',
                        'modified_time' => Carbon::now(),
                    ]
                );

                $files[] = $filePath;
            }

            // Generate ZIP file
            $zipFileName = "$namaPelatihan-$batch-$jenisSertifikat.zip";
            $zipFilePath = "qr-codes/$namaPelatihan/$batch/$zipFileName";

            $zip = new \ZipArchive();
            $zipTempFile = tempnam(sys_get_temp_dir(), 'zip');
            $zip->open($zipTempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            foreach ($files as $file) {
                $fileContent = Storage::disk('minio')->get($file);
                $zip->addFromString(basename($file), $fileContent);
            }

            $zip->close();
            Storage::disk('minio')->put($zipFilePath, file_get_contents($zipTempFile));
            unlink($zipTempFile);

            return response()->json([
                'message' => 'QR Codes berhasil di-generate',
                'status' => 'success',
                'download_url' => env('MINIO_URL') . '/' . env('MINIO_BUCKET') . '/' . $zipFilePath,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghasilkan QR Code',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detailSertifikat($certificateNumber)
    {
        // Cari sertifikat berdasarkan certificate_number_kompetensi
        $sertifikat = Sertifikat::with([
            'pendaftar',
            'pendaftaran.agendaPelatihan.pelatihan'
        ])
        ->where('certificate_number_kompetensi', $certificateNumber)
        ->orWhere('certificate_number_kehadiran', $certificateNumber)
        ->first();

        // Jika tidak ditemukan, kembalikan pesan error
        if (!$sertifikat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Certificate not found',
            ], 404);
        }

        // Strukturkan data untuk response
        $response = [
            'pendaftar' => $sertifikat->pendaftar,
            'pelatihan' => $sertifikat->pendaftaran->agendaPelatihan->pelatihan ?? null,
            'agendaPelatihan' => $sertifikat->pendaftaran->agendaPelatihan ?? null,
            'sertifikat' => $sertifikat,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $response,
        ], 200);
    }


}
