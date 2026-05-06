<?php

declare(strict_types=1);

namespace Org\Base\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('base::base.admin.dashboard');
    }
}
