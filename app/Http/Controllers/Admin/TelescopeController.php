<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TelescopeController extends BaseAdminController
{
    public function index()
    {
        // Redirection vers Telescope si installé
        return redirect('/telescope');
    }
}
