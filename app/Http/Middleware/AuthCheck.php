<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Admin;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    public function handle($request, Closure $next, $role = null)
    {
        $user = Session::get('user');

        // Cek apakah ada session user
        if (!$user) {
            if ($role === 'admin' && $request->path() !== 'admin') {
                return redirect('/admin');
            } elseif ($role === 'pendaftar' && $request->path() !== 'login-page') {
                return redirect('/login-page');
            }
        } else {
            // Cek role di session dan lakukan pengecekan di database
            if ($role === 'admin' && $user['role'] === 'admin') {
                // Cek di tabel Admin apakah is_deleted = true
                $admin = Admin::where('id_admin', $user['id_admin'])->where('is_deleted', true)->first();
                if ($admin) {
                    Session::forget('user');
                    return redirect('/admin')->with('error', 'Akun admin Anda telah dihapus.');
                }
            }

            if ($role === 'pendaftar' && $user['role'] === 'pendaftar') {
                // Cek di tabel User apakah is_deleted = true
                $pendaftar = Pendaftar::where('id_pendaftar', $user['id_pendaftar'])->where('is_deleted', true)->first();
                if ($pendaftar) {
                    Session::forget('user');
                    return redirect('/login-page')->with('error', 'Akun Anda telah dihapus.');
                }
            }
        }

        // Jika role tidak sesuai, maka lakukan redirect ke halaman error
        if (isset($user['role'])) {
            if ($role === 'admin' && $user['role'] !== 'admin' && $request->path() !== 'admin') {
                return redirect('/admin')->with('error', 'Unauthorized access.');
            }

            if ($role === 'pendaftar' && $user['role'] !== 'pendaftar' && $request->path() !== 'login-page') {
                return redirect('/login-page')->with('error', 'Unauthorized access.');
            }
        }

        return $next($request);
    }
}
