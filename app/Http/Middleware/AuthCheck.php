<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    public function handle($request, Closure $next)
    {
        $user = Session::get('user');

        // return response()->json($user);
        
        if (!$user) {
            return redirect('/login-page');
        }

        return $next($request);
    }
}
