<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterPesertaController extends Controller
{
    public function index()
    {
        return view('master/peserta/IndexPeserta');
    }
}
