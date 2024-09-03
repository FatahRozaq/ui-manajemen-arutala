<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PesertaPelatihanController extends Controller
{
    public function index()
    {
        return view('master/PesertaPelatihan/IndexPesertaPelatihan');
    }

    public function show()
    {
        return view('master/PesertaPelatihan/UpdateStatusPembayaran');
    }
}
