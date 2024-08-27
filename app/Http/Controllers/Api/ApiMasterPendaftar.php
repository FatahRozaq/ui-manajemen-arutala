<?php

namespace App\Http\Controllers\Api;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiMasterPendaftar extends Controller
{
    public function index()
    {
        $pendaftar = Pendaftar::get();

        return response()->json($pendaftar);
    }
}
