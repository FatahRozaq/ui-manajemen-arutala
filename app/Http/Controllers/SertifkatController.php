<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SertifkatController extends Controller
{
    public function index()
    {
        return view('sertifikat/IndexSertifikat');
    }
}
