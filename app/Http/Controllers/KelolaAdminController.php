<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaAdminController extends Controller
{
    public function index()
    {
        return view('admin/IndexAdmin');
    }

    public function show()
    {
        return view('admin/DetailAdmin');
    }

    public function add()
    {
        return view('admin/TambahAdmin');
    }

    public function update()
    {
        return view('admin/EditAdmin');
    }
}
