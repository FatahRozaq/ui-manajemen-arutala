<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterPelatihanController extends Controller
{
    public function index()
    {
        return view('master/pelatihan/IndexPelatihan');
    }

    public function form()
    {
        return view('master/pelatihan/FormPelatihan');
    }
}
