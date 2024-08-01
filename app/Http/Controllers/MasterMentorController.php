<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterMentorController extends Controller
{
    public function index()
    {
        return view('master/mentor/IndexMentor');
    }
}
