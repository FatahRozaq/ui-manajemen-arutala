<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AgendaPelatihan;
use App\Models\PendaftaranEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiPendaftaranEventController extends Controller
{
    public function show($idAgenda)
    {
        try {
            $pendaftar = Auth::user();
            $agenda = AgendaPelatihan::with('pelatihan')->where('id_agenda', $idAgenda)->first();

            if (!$agenda) {
                return response()->json([
                    'message' => 'Agenda tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'pendaftar' => $pendaftar,
                'agenda' => $agenda,
                'pelatihan' => $agenda->pelatihan, // Mengambil data pelatihan yang terkait
                'message' => 'Data berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_agenda' => 'required|exists:agenda_pelatihan,id_agenda',
                'status_pembayaran' => 'required|string|max:255',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_kontak' => [
                    'required',
                    'string',
                    'max:25',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/^0/', $value) || preg_match('/^62/', $value) || preg_match('/^\+62/', $value)) {
                            $fail('Nomor kontak tidak boleh diawali dengan 0, 62, atau +62.');
                        }
                    }
                ],
                'aktivitas' => 'required|string|max:255',
                'nama_instansi' => 'nullable|string|max:255',
                'provinsi' => 'required|string|max:255',
                'kab_kota' => 'required|string|max:255',
            ], [
                'id_agenda.required' => 'Agenda wajib dipilih.',
                'id_agenda.exists' => 'Agenda tidak valid atau tidak tersedia.',
                'status_pembayaran.required' => 'Status pembayaran wajib diisi.',
                'status_pembayaran.string' => 'Status pembayaran harus berupa teks.',
                'status_pembayaran.max' => 'Status pembayaran tidak boleh lebih dari 255 karakter.',
                'nama.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'no_kontak.required' => 'Nomor kontak wajib diisi.',
                'no_kontak.string' => 'Nomor kontak harus berupa teks.',
                'no_kontak.max' => 'Nomor kontak tidak boleh lebih dari 25 karakter.',
                'aktivitas.required' => 'Aktivitas wajib diisi.',
                'aktivitas.string' => 'Aktivitas harus berupa teks.',
                'nama_instansi.string' => 'Nama Instansi harus berupa teks.',
                'nama_instansi.max' => 'Nama Instansi tidak boleh lebih dari 255 karakter.',
                'provinsi.required' => 'Provinsi wajib diisi.',
                'provinsi.string' => 'Provinsi harus berupa teks.',
                'kab_kota.required' => 'Kab/Kota wajib diisi.',
                'kab_kota.string' => 'Kab/Kota harus berupa teks.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $pendaftar = Pendaftar::find(Auth::id());

            if ($pendaftar) {
                $pendaftar->update([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_kontak' => '+62' . ltrim($request->no_kontak, '0'), // Menambahkan +62 dan menghapus awalan 0
                    'aktivitas' => $request->aktivitas,
                    'nama_instansi' => $request->nama_instansi,
                    'provinsi' => $request->provinsi,
                    'kab_kota' => $request->kab_kota,
                    'modified_by' => $request->email,
                    'modified_time' => now(),
                ]);
            } else {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan.',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $agenda = AgendaPelatihan::find($request->id_agenda);
            if (!$agenda) {
                return response()->json([
                    'message' => 'Agenda tidak tersedia.',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $pendaftaranEvent = PendaftaranEvent::create([
                'id_peserta' => $pendaftar->id_pendaftar,
                'id_agenda' => $request->id_agenda,
                'status_pembayaran' => $request->status_pembayaran,
                'status_kehadiran' => 'Belum Hadir', // Default status kehadiran
                'created_by' => $request->email,
                'created_time' => now(),
                'modified_by' => $request->email,
                'modified_time' => now(),
                'is_deleted' => false
            ]);

            return response()->json([
                'data' => $pendaftaranEvent,
                'message' => 'Pendaftaran berhasil dilakukan',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan pendaftaran',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }





    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'id_agenda' => 'required',
    //             'status_pembayaran' => 'required|string|max:255',
    //             'status_kehadiran' => 'required|string|max:255'
    //         ], [
    //             'id_agenda.required' => 'Agenda wajib dipilih.',
    //             'id_agenda.exists' => 'Agenda tidak valid atau tidak tersedia.',
    //             'status_pembayaran.required' => 'Status pembayaran wajib diisi.',
    //             'status_pembayaran.string' => 'Status pembayaran harus berupa teks.',
    //             'status_pembayaran.max' => 'Status pembayaran tidak boleh lebih dari 255 karakter.',
    //             'status_kehadiran.required' => 'Status kehadiran wajib diisi.',
    //             'status_kehadiran.string' => 'Status kehadiran harus berupa teks.',
    //             'status_kehadiran.max' => 'Status kehadiran tidak boleh lebih dari 255 karakter.'
    //         ]);

    //         $agenda = AgendaPelatihan::find($request->id_agenda);
    //         if (!$agenda) {
    //             return response()->json([
    //                 'message' => 'Agenda tidak tersedia.',
    //                 'statusCode' => Response::HTTP_NOT_FOUND,
    //                 'status' => 'error'
    //             ], Response::HTTP_NOT_FOUND);
    //         }

    //         $pendaftaranEvent = PendaftaranEvent::create([
    //             'id_peserta' => Auth::id(),
    //             'id_agenda' => $request->id_agenda,
    //             'status_pembayaran' => $request->status_pembayaran,
    //             'status_kehadiran' => $request->status_kehadiran,
    //             'created_by' => Auth::user()->email,
    //             'created_time' => now(),
    //             'modified_by' => Auth::user()->email,
    //             'modified_time' => now(),
    //             'is_deleted' => false
    //         ]);

    //         return response()->json([
    //             'data' => $pendaftaranEvent,
    //             'message' => 'Pendaftaran berhasil dilakukan',
    //             'statusCode' => Response::HTTP_OK,
    //             'status' => 'success'
    //         ], Response::HTTP_OK);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan saat menyimpan pendaftaran',
    //             'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'status' => 'error',
    //             'error' => $e->getMessage()
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

}