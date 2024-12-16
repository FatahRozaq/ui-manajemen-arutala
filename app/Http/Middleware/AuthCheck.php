<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthCheck
{
    public function handle($request, Closure $next, $role = null)
    {
        // Retrieve user session
        $user = Session::get('user');

        // Scenario 1: No user session exists
        if (!$user) {
            // Redirect based on attempted role access
            return $this->handleNoSession($request, $role);
        }

        // Scenario 2: Validate user role and account status
        $redirectResponse = $this->validateUserAccount($user, $role);
        if ($redirectResponse) {
            return $redirectResponse;
        }

        // Scenario 3: Check role-based access
        $roleCheckResponse = $this->checkRoleAccess($request, $user, $role);
        if ($roleCheckResponse) {
            return $roleCheckResponse;
        }

        // Add cache prevention headers
        return $this->addCachePreventionHeaders($next($request));
    }

    /**
     * Handle scenarios when no user session exists
     */
    protected function handleNoSession(Request $request, $role)
    {
        // Specific redirects based on attempted role access
        if ($role === 'admin' && $request->path() !== 'admin') {
            return redirect('/admin')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($role === 'pendaftar' && $request->path() !== 'login-page') {
            return redirect('/login-page')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    /**
     * Validate user account status
     */
    protected function validateUserAccount($user, $role)
    {
        // Check deleted status for admin
        if ($role === 'admin' && $user['role'] === 'admin') {
            $admin = Admin::where('id_admin', $user['id_admin'])
                ->where('is_deleted', true)
                ->first();
            
            if ($admin) {
                Session::forget('user');
                return redirect('/admin')->with('error', 'Akun admin Anda telah dihapus.');
            }
        }

        // Check deleted status for pendaftar
        if ($role === 'pendaftar' && $user['role'] === 'pendaftar') {
            $pendaftar = Pendaftar::where('id_pendaftar', $user['id_pendaftar'])
                ->where('is_deleted', true)
                ->first();
            
            if ($pendaftar) {
                Session::forget('user');
                return redirect('/login-page')->with('error', 'Akun Anda telah dihapus.');
            }
        }

        return null;
    }

    /**
     * Check role-based access
     */
    protected function checkRoleAccess(Request $request, $user, $role)
    {
        // Ensure role exists in session
        if (!isset($user['role'])) {
            return redirect('/login-page')->with('error', 'Sesi tidak valid.');
        }

        // Admin role check
        if ($role === 'admin' && $user['role'] !== 'admin' && $request->path() !== 'admin') {
            return redirect('/admin')->with('error', 'Akses tidak diizinkan.');
        }

        // Pendaftar role check
        if ($role === 'pendaftar' && $user['role'] !== 'pendaftar' && $request->path() !== 'login-page') {
            return redirect('/login-page')->with('error', 'Akses tidak diizinkan.');
        }

        return null;
    }

    /**
     * Add cache prevention headers
     */
    protected function addCachePreventionHeaders($response)
    {
        return $response
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}