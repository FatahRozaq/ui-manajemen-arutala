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
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiProfilePeserta extends Controller
{
    public function show()
{
    try {
        // $idUser = Auth::id();
        // $user = JWTAuth::parseToken()->authenticate();
        // $idUser = $user->id;
        $idUser = auth('api')->id();
        // dd($idUser);
        $pendaftar = Pendaftar::where('id_pendaftar', $idUser)->first();

        if (!$pendaftar) {
            return response()->json([
                'message' => 'Pendaftar tidak ditemukan',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'status' => 'error'
            ], Response::HTTP_NOT_FOUND);
        }

        // Hilangkan awalan +62 dari nomor kontak untuk ditampilkan
        $pendaftar->no_kontak = ltrim($pendaftar->no_kontak, '+62');

        return response()->json([
            'data' => $pendaftar,
            'message' => 'Profile pendaftar berhasil diambil',
            'statusCode' => Response::HTTP_OK,
            'status' => 'success'
        ], Response::HTTP_OK);
    } catch(Exception $e) {
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
        'email' => 'required|email|max:255|unique:pendaftar,email,' . Auth::id() . ',id_pendaftar',
        'no_kontak' => [
            'required',
            'string',
            'max:25',
            'regex:/^(?!0)(?!62)(?!\+62)[0-9]+$/'
        ],
        'aktivitas' => 'required|string|max:15',
        'nama_instansi' => 'nullable|string|max:50',
        'provinsi' => 'required|string|max:50',
        'kab_kota' => 'required|string|max:50',
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
        'no_kontak.required' => 'Nomor kontak harus diisi',
        'no_kontak.string' => 'Nomor kontak harus berupa teks',
        'no_kontak.max' => 'Nomor kontak tidak boleh lebih dari 25 karakter',
        'no_kontak.regex' => 'Nomor kontak tidak boleh diawali dengan 0, 62, atau +62. Gunakan nomor tanpa kode negara atau awalan 0.',
        'aktivitas.required' => 'Aktivitas harus diisi',
        'aktivitas.string' => 'Aktivitas harus berupa teks',
        'aktivitas.max' => 'Aktivitas tidak boleh lebih dari 15 karakter',
        'nama_instansi.string' => 'Nama instansi harus berupa teks',
        'nama_instansi.max' => 'Nama instansi tidak boleh lebih dari 50 karakter',
        'provinsi.required' => 'Provinsi harus diisi',
        'provinsi.string' => 'Provinsi harus berupa teks',
        'provinsi.max' => 'Provinsi tidak boleh lebih dari 50 karakter',
        'kab_kota.required' => 'Kabupaten/Kota harus diisi',
        'kab_kota.string' => 'Kabupaten/Kota harus berupa teks',
        'kab_kota.max' => 'Kabupaten/Kota tidak boleh lebih dari 50 karakter',
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

}
