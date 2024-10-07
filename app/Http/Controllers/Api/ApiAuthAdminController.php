<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;

class ApiAuthAdminController extends Controller
{
    public function register(Request $request)
    {
        try {
            $messages = [
                'nama.required' => 'Nama wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.regex' => 'Email harus berakhiran dengan domain valid seperti .com, .org, atau .net.',
                'password.required' => 'Password wajib diisi.',
                'password.string' => 'Password harus berupa teks.',
                'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            ];

            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                    'max:255',
                    'unique:admin'
                ],
                'password' => 'required|string|min:8',
            ], $messages);

            $pendaftar = Admin::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_by' => 'Admin', 
                'created_time' => Carbon::now(),
            ]);

            return response()->json([
                'data' => $pendaftar,
                'message' => 'Pendaftaran admin berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Pendaftaran admin gagal',
                'errors' => $e->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat pendaftaran admin',
                'errors' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        try {
            $messages = [
                'email.required' => 'Email wajib diisi.',
                'email.string' => 'Email harus berupa teks.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'password.required' => 'Password wajib diisi.',
                'password.string' => 'Password harus berupa teks.',
            ];

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ], $messages);

            $credentials = $request->only('email', 'password');

            $admin = Admin::where('email', $credentials['email'])
                ->where('is_deleted', false)
                ->first();

            if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah, atau akun telah dihapus'],
                ]);
            }

            // Generate token setelah validasi
            if (! $token = auth('admin')->login($admin)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah'],
                ]);
            }

            return response()->json([
                'data' => $admin,
                'token' => $token,
                'message' => 'Login berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login gagal',
                'error' => $e->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Tidak dapat membuat token',
                'error' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Logout berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Logout gagal',
                'error' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show()
    {
        try {
            // $idUser = Auth::id();
            // $user = JWTAuth::parseToken()->authenticate();
            // $idUser = $user->id;
            $idUser = auth('admin')->id();
            // dd($idUser);
            $admin = Admin::where('id_admin', $idUser)->first();

            if (!$admin) {
                return response()->json([
                    'message' => 'Admin tidak ditemukan',
                    'statusCode' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $admin,
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

}
