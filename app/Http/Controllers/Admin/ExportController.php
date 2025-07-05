<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExportController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('permission:admin.dashboard');
    }

    public function index(Request $request): View
    {
        return view('pages.admin.exports.index');
    }
}
