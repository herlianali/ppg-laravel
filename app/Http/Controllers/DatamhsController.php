<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatamhsController extends Controller
{
    public function index()
    {
        return view('datamhs.index');
    }
}
