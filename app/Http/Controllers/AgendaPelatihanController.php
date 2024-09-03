<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaPelatihanController extends Controller
{
    public function index()
    {
        return view('master/AgendaPelatihan/IndexAgendaPelatihan');
    }


    public function show()
    {
        return view('master/AgendaPelatihan/DetailAgendaPelatihan');
    }

    public function formAgenda()
    {
        return view('master/AgendaPelatihan/FormAgendaPelatihan');
    }

    public function updateAgenda()
    {
        return view('master/AgendaPelatihan/UpdateAgendaPelatihan');
    }
}
