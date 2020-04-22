<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'dashboard');
        view()->share('pagetitle', 'Dashboard');
        $this->request = $request;
    }

    public function show()
    {
        return view('super.dashboard.show');
    }
}
