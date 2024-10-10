<?php
namespace App\Http\Controllers\Api;

use App\Models\Mentor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiMentorController extends Controller
{
    public function index()
    {
        try {
            $mentors = Mentor::where('is_deleted', false)
                            ->orderBy('nama_mentor', 'asc')
                            ->get();

            return response()->json([
                'data' => $mentors,
                'message' => 'Daftar mentor berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil daftar mentor',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mentor' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                'max:255',
                'unique:mentor'
            ],
            'no_kontak' => [
                'required',
                'string',
                'regex:/^(?!0|62|\+62)[0-9]+$/',
                'min:10',  
                'max:15'
            ],
            'aktivitas' => 'nullable|string|max:15',
        ], [
            'nama_mentor.required' => 'Nama mentor harus diisi.',
            'nama_mentor.string' => 'Nama mentor harus berupa string.',
            'nama_mentor.max' => 'Nama mentor tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.regex' => 'Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_kontak.required' => 'Nomor kontak harus diisi.',
            'no_kontak.string' => 'Nomor kontak harus berupa string.',
            'no_kontak.min' => 'Nomor kontak harus minimal 10 digit.',
            'no_kontak.max' => 'Nomor kontak tidak boleh lebih dari 15 karakter.',
            'no_kontak.regex' => 'Nomor kontak tidak boleh diawali dengan 0, 62, atau +62 dan hanya boleh berisi angka.',
            'aktivitas.required' => 'Aktivitas harus diisi.',
            'aktivitas.string' => 'Aktivitas harus berupa string.',
            'aktivitas.max' => 'Aktivitas tidak boleh lebih dari 15 karakter.',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $no_kontak = '+62' . ltrim($request->input('no_kontak'), '0');

        try {
            $mentor = Mentor::create([
                'nama_mentor' => $request->nama_mentor,
                'email' => $request->email,
                'no_kontak' => $no_kontak,
                'aktivitas' => $request->aktivitas,
                'created_by' => 'Admin',
                'created_time' => Carbon::now(),
                'is_deleted' => false, 
            ]);

            return response()->json([
                'data' => $mentor,
                'message' => 'Mentor berhasil dibuat',
                'statusCode' => Response::HTTP_CREATED,
                'status' => 'success'
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat mentor',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $mentor = Mentor::where('id_mentor', $id)
                            ->where('is_deleted', false)
                            ->first();

            if (!$mentor) {
                return response()->json([
                    'message' => 'Mentor tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $mentor->no_kontak = ltrim($mentor->no_kontak, '+62');

            return response()->json([
                'data' => $mentor,
                'message' => 'Mentor berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil mentor',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_mentor' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                'max:255',
                'unique:mentor,email,' . $id . ',id_mentor'
            ],
            'no_kontak' => [
                'required',
                'string',
                'regex:/^(?!0|62|\+62)[0-9]+$/',
                'min:10',  
                'max:15'
            ],
            'aktivitas' => 'nullable|string|max:15',
        ], [
            'nama_mentor.required' => 'Nama mentor harus diisi.',
            'nama_mentor.string' => 'Nama mentor harus berupa string.',
            'nama_mentor.max' => 'Nama mentor tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.regex' => 'Email harus berakhiran dengan domain valid seperti .com, .org, atau .net.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_kontak.required' => 'Nomor kontak harus diisi.',
            'no_kontak.string' => 'Nomor kontak harus berupa string.',
            'no_kontak.min' => 'Nomor kontak harus minimal 10 digit.',
            'no_kontak.max' => 'Nomor kontak tidak boleh lebih dari 15 karakter.',
            'no_kontak.regex' => 'Nomor kontak tidak boleh diawali dengan 0, 62, atau +62 dan hanya boleh berisi angka.',
            'aktivitas.required' => 'Aktivitas harus diisi.',
            'aktivitas.string' => 'Aktivitas harus berupa string.',
            'aktivitas.max' => 'Aktivitas tidak boleh lebih dari 15 karakter.',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $mentor = Mentor::where('id_mentor', $id)
                            ->where('is_deleted', false)
                            ->first();

            if (!$mentor) {
                return response()->json([
                    'message' => 'Mentor tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }



            $mentor->update([
                'nama_mentor' => $request->nama_mentor,
                'email' => $request->email,
                'no_kontak' => '+62' . ltrim($request->input('no_kontak'), '0'),
                'aktivitas' => $request->aktivitas,
                'modified_by' => 'Admin',
                'modified_time' => Carbon::now(),
            ]);

            return response()->json([
                'data' => $mentor,
                'message' => 'Mentor berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui mentor',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $mentor = Mentor::where('id_mentor', $id)
                            ->where('is_deleted', false)
                            ->first();

            if (!$mentor) {
                return response()->json([
                    'message' => 'Mentor tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $namaMentor = $mentor->nama_mentor;

            $mentor->update([
                'is_deleted' => true,
                'modified_by' => 'Admin',
                'modified_time' => Carbon::now(),
            ]);

            return response()->json([
                'message' => "Mentor $namaMentor berhasil dihapus",
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus mentor',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
