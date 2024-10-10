<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiKelolaAdminController extends Controller
{
    public function index()
    {
        try {
            $admins = Admin::where('is_deleted', false)
                            ->orderBy('nama', 'asc')
                            ->get();

            return response()->json([
                'data' => $admins,
                'message' => 'Data admin berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data admin',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $admin = Admin::where('id_admin', $id)
                          ->where('is_deleted', false)
                          ->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $admin,
                'message' => 'Admin berhasil diambil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil admin',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                'max:255',
                'unique:admin,email,' . $id . ',id_admin'
            ],
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.string' => 'Nama harus berupa string.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .',
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
            $admin = Admin::where('id_admin', $id)
                          ->where('is_deleted', false)
                          ->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $admin->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'modified_by' => 'Admin',
                'modified_time' => Carbon::now(),
            ]);

            return response()->json([
                'data' => $admin,
                'message' => 'Admin berhasil diperbarui',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui admin',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $admin = Admin::where('id_admin', $id)
                          ->where('is_deleted', false)
                          ->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            $admin->update([
                'is_deleted' => true,
                'modified_by' => 'Admin',
                'modified_time' => Carbon::now(),
            ]);

            return response()->json([
                'message' => "{$admin->nama} berhasil dihapus",
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus admin',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
