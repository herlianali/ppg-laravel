<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatkurController extends Controller
{
    public function index()
    {
        return view('matkur.index');
    }
}
