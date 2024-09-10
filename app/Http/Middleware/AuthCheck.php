<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    public function handle($request, Closure $next, $role = null)
    {
        $user = Session::get('user');

        if (!$user) {
            if ($role === 'admin' && $request->path() !== 'admin/login-admin') {
                return redirect('/admin/login-admin');
            } elseif ($role === 'pendaftar' && $request->path() !== 'login-page') {
                return redirect('/login-page');
            }
        }

        if (isset($user['role'])) {
            if ($role === 'admin' && $user['role'] !== 'admin' && $request->path() !== 'admin/login-admin') {
                return redirect('/admin/login-admin')->with('error', 'Unauthorized access.');
            }

            if ($role === 'pendaftar' && $user['role'] !== 'pendaftar' && $request->path() !== 'login-page') {
                return redirect('/login-page')->with('error', 'Unauthorized access.');
            }
        } else {
            if ($role === 'admin' && $request->path() !== 'admin/login-admin') {
                return redirect('/admin/login-admin');
            } elseif ($role === 'pendaftar' && $request->path() !== 'login-page') {
                return redirect('/login-page');
            }
        }

        return $next($request);
    }
}
