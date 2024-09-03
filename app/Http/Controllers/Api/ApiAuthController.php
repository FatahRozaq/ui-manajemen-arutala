<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
                'password.required' => 'Password wajib diisi.',
                'password.string' => 'Password harus berupa teks.',
                'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
                'no_kontak.required' => 'Nomor kontak wajib diisi.',
                'no_kontak.regex' => 'Nomor kontak tidak boleh diawali dengan 0, 62, atau +62 dan hanya boleh berisi angka.',
                'no_kontak.max' => 'Nomor kontak tidak boleh lebih dari 25 karakter.',
            ];

            $request->validate([
                'email' => 'required|string|email|max:255|unique:pendaftar',
                'password' => 'required|string|min:8',
                'no_kontak' => [
                    'required',
                    'string',
                    'regex:/^(?!0|62|\+62)[0-9]+$/',
                    'max:25'
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

            $token = $pendaftar->createToken('auth_token')->plainTextToken;

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

            $pendaftar = Pendaftar::where('email', $request->email)->first();

            if (! $pendaftar || ! Hash::check($request->password, $pendaftar->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Email atau password salah'],
                ]);
            }

            $token = $pendaftar->createToken('auth_token')->plainTextToken;

            return response()->json([
                'data' => $pendaftar,
                'token' => $token,
                'message' => 'Login berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login gagal',
                'error' => $e->errors(),  // Memastikan semua error validasi dikembalikan
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'status' => 'error'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout berhasil',
                'statusCode' => Response::HTTP_OK,
                'status' => 'success'
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Logout gagal',
                'error' => $e->getMessage(),
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => 'error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
