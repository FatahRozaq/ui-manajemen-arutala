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
            return Response::download($filePath, $filename)
                ->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
                ]);
        } else {
            return abort(404, 'File not found');
        }
    }
}
