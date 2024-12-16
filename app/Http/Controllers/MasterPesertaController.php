<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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

    public function download()
{
    $filename = 'File Template Import Data Pendaftar.xlsx';
    $filePath = public_path('assets/file/' . $filename);

    if (File::exists($filePath)) {
        return Response::download($filePath, $filename);
    } else {
        return abort(404, 'File not found');
    }
}
}
