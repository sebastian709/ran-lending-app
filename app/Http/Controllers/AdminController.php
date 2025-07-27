<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.pages.main.index');
    }

    public function blankTesting()
    {
        return view('admin.testing-only.blankpage');
    }
}
