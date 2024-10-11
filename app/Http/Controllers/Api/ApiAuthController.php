<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $messages = [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.regex' => 'Email harus berakhiran dengan domain valid .com, .org, .net, .edu, gov, .mil, .int, .info, .co, .id .',
                'password.required' => 'Password wajib diisi.',
                'password.string' => 'Password harus berupa teks.',
                'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
                'no_kontak.required' => 'Kontak wajib diisi.',
                'no_kontak.regex' => 'Kontak tidak boleh diawali dengan 0, 62, atau +62 dan hanya boleh berisi angka.',
                'no_kontak.min' => 'Kontak harus minimal 10 digit.',
                'no_kontak.max' => 'Kontak tidak boleh lebih dari 15 karakter.',
            ];

            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email',
                    'regex:/^[\w\.-]+@[a-zA-Z\d\.-]+\.(com|org|net|edu|gov|mil|int|info|co|id)$/',
                    'max:255',
                    'unique:pendaftar'
                ],
                'password' => 'required|string|min:8',
                'no_kontak' => [
                    'required',
                    'string',
                    'regex:/^\+?[1-9][0-9]{9,14}$/',
                    'min:10',  
                    'max:15'
                ],
            ], $messages);

            $no_kontak = '+62' . ltrim($request->input('no_kontak'), '0');

            $pendaftar = Pendaftar::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_kontak' => $no_kontak,
                'created_by' => 'Pendaftar', 
                'created_time' => Carbon::now(),
            ]);

            // Buat token JWT untuk pengguna yang baru didaftarkan
            $token = JWTAuth::fromUser($pendaftar);

            return response()->json([
                'data' => $pendaftar,
                'token' => $token,
                'message' => 'Pendaftaran berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Pendaftaran gagal',
                'error' => $e->errors(),
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat pendaftaran',
                'error' => $e->getMessage(),
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

            $user = Pendaftar::where('email', $credentials['email'])
                ->where('is_deleted', false)
                ->first();

            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah, atau akun telah dihapus'],
                ]);
            }

            if (! $token = auth('api')->login($user)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah'],
                ]);
            }

            return response()->json([
                'data' => $user,
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
            // Hapus token pengguna yang sedang login
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

    public function loginAdmin(Request $request)
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

            if (! $token = auth('admin')->attempt($credentials)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah'],
                ]);
            }

            $admin = auth('admin')->user();

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

}
