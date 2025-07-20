<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpleDashboardController extends Controller
{
    public function index()
    {
        return view('simple-dashboard');
    }
}
