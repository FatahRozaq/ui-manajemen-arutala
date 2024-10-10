<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function LoginPage()
    {
        return view('auth/Login');
    }

    public function RegisterPage()
    {
        return view('auth/Register');
    }

    public function saveSession(Request $request)
    {
        $user = $request->input('user');

        if ($user) {
            $role = isset($user['id_admin']) ? 'admin' : 'pendaftar';
            $user['role'] = $role; 

            Session::put('user', $user);
            return response()->json(['message' => 'Session saved successfully!'], 200);
        }

        return response()->json(['message' => 'Failed to save session!'], 400);
    }


    public function logout()
    {
        if (Session::has('user')) {
            Session::flush();
        }

        return redirect()->route('login.page')->with('success', 'Logged out successfully!');
    }

}
