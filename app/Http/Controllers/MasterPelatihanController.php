<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterPelatihanController extends Controller
{
    public function index()
    {
        return view('master/pelatihan/IndexPelatihan');
    }
}
