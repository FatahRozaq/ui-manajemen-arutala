<?php

namespace App\Http\Controllers\Api;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;

class ApiProfilePeserta extends Controller
{
    public function show()
    {
        try {
            // $idUser = Auth::id();
            // $user = JWTAuth::parseToken()->authenticate();
            // $idUser = $user->id;
            $idUser = auth('api')->id();
            $pendaftar = Pendaftar::where('id_pendaftar', $idUser)->first();

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            // Hilangkan awalan +62 dari Kontak untuk ditampilkan
            $pendaftar->no_kontak = ltrim($pendaftar->no_kontak, '+62');

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Profile pendaftar berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Profile pendaftar gagal diambil',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                'max:255',
                'unique:pendaftar,email,' . Auth::id() . ',id_pendaftar'
            ],
            'no_kontak' => [
                'required',
                'string',
                'regex:/^\+?[1-9][0-9]{9,14}$/',
                'min:10',  
                'max:15'
            ],
            'aktivitas' => 'required|string|max:15',
            'nama_instansi' => 'nullable|string|max:50',
            'provinsi' => [
                'required',
                'string',
                'max:50',
                'not_in:Pilih Provinsi'
            ],
            'kab_kota' => [
                'required',
                'string',
                'max:50',
                'not_in:Pilih Kab/Kota'
            ],
            'linkedin' => 'nullable|string|max:50',
            'modified_by' => 'nullable|string|max:255'
        ], [
            'nama.required' => 'Nama lengkap harus diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter',
            'email.unique' => 'Email sudah digunakan. Gunakan email yang lain',
            'email.regex' => 'Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .',
            'no_kontak.required' => 'Kontak harus diisi',
            'no_kontak.string' => 'Kontak harus berupa teks',
            'no_kontak.min' => 'Kontak harus minimal 10 digit.',
            'no_kontak.max' => 'Kontak tidak boleh lebih dari 15 karakter.',
            'no_kontak.regex' => 'Kontak tidak boleh diawali dengan 0 dan tidak boleh memakai spesial karakter',
            'aktivitas.required' => 'Aktivitas harus diisi',
            'aktivitas.string' => 'Aktivitas harus berupa teks',
            'aktivitas.max' => 'Aktivitas tidak boleh lebih dari 15 karakter',
            'nama_instansi.string' => 'Nama instansi harus berupa teks',
            'nama_instansi.max' => 'Nama instansi tidak boleh lebih dari 50 karakter',
            'provinsi.required' => 'Provinsi harus diisi',
            'provinsi.string' => 'Provinsi harus berupa teks',
            'provinsi.max' => 'Provinsi tidak boleh lebih dari 50 karakter',
            'provinsi.not_in' => 'Provinsi harus diisi terlebih dahulu',
            'kab_kota.required' => 'Kabupaten/Kota harus diisi',
            'kab_kota.string' => 'Kabupaten/Kota harus berupa teks',
            'kab_kota.max' => 'Kabupaten/Kota tidak boleh lebih dari 50 karakter',
            'kab_kota.not_in' => 'Kabupaten/Kota harus dipilih terlebih dahulu',
            'linkedin.string' => 'LinkedIn harus berupa teks',
            'linkedin.max' => 'LinkedIn tidak boleh lebih dari 50 karakter',
            'modified_by.string' => 'Modified by harus berupa teks',
            'modified_by.max' => 'Modified by tidak boleh lebih dari 255 karakter',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $idUser = auth('api')->id();
            $pendaftar = Pendaftar::where('id_pendaftar', $idUser)->first();

            if (!$pendaftar) {
                return response()->json([
                    'message' => 'Pendaftar tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $pendaftar->nama = $request->input('nama');
            $pendaftar->email = $request->input('email');
            $pendaftar->no_kontak = '+62' . ltrim($request->input('no_kontak'), '0');
            $pendaftar->aktivitas = $request->input('aktivitas');
            $pendaftar->nama_instansi = $request->input('nama_instansi');
            $pendaftar->provinsi = $request->input('provinsi');
            $pendaftar->kab_kota = $request->input('kab_kota');
            $pendaftar->linkedin = $request->input('linkedin');
            $pendaftar->modified_by = "Pendaftar";
            $pendaftar->modified_time = now();
            $pendaftar->save();

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Profile pendaftar berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Profile pendaftar gagal diperbarui',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            // Validasi input
            $messages = [
                'current_password.required' => 'Password saat ini wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.min' => 'Password baru harus terdiri dari minimal 8 karakter.',
            ];

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8',
            ], $messages);

            // Ambil pengguna saat ini
            $idUser = auth('api')->id();
            $pendaftar = Pendaftar::where('id_pendaftar', $idUser)->first();

            // Verifikasi password lama
            if (!Hash::check($request->current_password, $pendaftar->password)) {
                // Tambahkan error secara manual ke validator
                $validator->errors()->add('current_password', 'Password saat ini tidak cocok');
            }

            // Jika ada kesalahan pada validator (termasuk yang ditambahkan secara manual)
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'statusCode' => Response::HTTP_BAD_REQUEST,
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            // Update password baru
            $pendaftar->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'message' => 'Password berhasil diubah',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success',
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah password',
                'error' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
