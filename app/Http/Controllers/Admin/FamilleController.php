<?php

namespace App\Http\Controllers\Admin;

class FamilleController extends BaseAdminController
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
