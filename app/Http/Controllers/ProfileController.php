<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile/ShowProfile');
    }

    public function update()
    {
        return view('profile/EditProfile');
    }

    public function changePassword()
    {
        return view('profile/EditPassword');
    }
}
