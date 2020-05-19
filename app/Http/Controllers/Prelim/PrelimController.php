<?php

namespace App\Http\Controllers\Prelim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrelimController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'prelim');
        view()->share('pagetitle', 'Prelim');
        $this->request = $request;
    }

    public function schedule()
    {
        $view_arr = [];
        return view('prelim.schedule')->with($view_arr);
    }
    
    public function show()
    {
        $view_arr = [];
        return view('prelim.show')->with($view_arr);
    }
}
