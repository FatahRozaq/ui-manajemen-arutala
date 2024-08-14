<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
