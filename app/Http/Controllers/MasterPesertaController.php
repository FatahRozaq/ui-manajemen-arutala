<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterPesertaController extends Controller
{
    public function index()
    {
        return view('master/peserta/IndexPeserta');
    }

    public function detail()
    {
        return view('master/peserta/DetailPeserta');
    }

    public function edit()
    {
        return view('master/peserta/EditPeserta');
    }
}
