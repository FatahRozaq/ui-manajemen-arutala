<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Ambil token dari header Authorization
            $token = $request->bearerToken();

            // dd($token);
            if (!$token) {
                return response()->json(['message' => 'Token tidak ditemukan'], 401);
            }
            // Decode token JWT
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            dd($decoded);
            // Cari pengguna berdasarkan ID yang ada dalam payload token
            $userId = $decoded->sub; // 'sub' biasanya adalah ID pengguna
            
            if (!$userId) {
                return response()->json(['message' => 'Token tidak valid'], 401);
            }

            // Autentikasi pengguna dengan ID dari token JWT
            Auth::loginUsingId($userId);

        } catch (Exception $e) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa'], 401);
        }

        return $next($request);
    }
}
