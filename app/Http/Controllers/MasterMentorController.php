<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterMentorController extends Controller
{
    public function index()
    {
        return view('master/mentor/IndexMentor');
    }

    public function detail()
    {
        return view('master/mentor/DetailMentor');
    }

    public function update()
    {
        return view('master/mentor/UpdateDataMentor');
    }

    public function add()
    {
        return view('master/mentor/TambahMentor');
    }
}
