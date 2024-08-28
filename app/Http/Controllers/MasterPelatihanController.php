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

    public function showPelatihan()
    {
        return view('master/pelatihan/DetailPelatihan');
    }

    public function updatePelatihan()
    {
        return view('master/pelatihan/UpdatePelatihan');
    }


    public function show()
    {
        return view('master/AgendaPelatihan/IndexDetailAgenda');
    }

    public function agendaPelatihan()
    {
        return view('master/AgendaPelatihan/FormAgendaPelatihan');
    }
}
