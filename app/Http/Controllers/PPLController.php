<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PPLController extends Controller
{
    public function index()
    {
        return view('ppl.index');
    }
}
