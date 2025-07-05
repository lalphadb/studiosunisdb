<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscriptionSeminaireController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): View
    {
        return view('pages.admin.seminaires.inscriptions');
    }
}
