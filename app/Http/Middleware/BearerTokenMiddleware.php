<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class BearerTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Session::get('user');
        
        if (!$user) {
            // Jika tidak ada user di session, arahkan ke halaman login
            return redirect('/login');
        }

        return $next($request);
    }
}
