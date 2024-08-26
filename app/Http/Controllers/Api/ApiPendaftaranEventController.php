<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiPendaftaranEventController extends Controller
{
    public function show()
    {
        $pendaftar = Auth::user();
        // $agenda = 
    }
}
