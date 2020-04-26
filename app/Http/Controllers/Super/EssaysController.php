<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class EssaysController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'essays');
        view()->share('pagetitle', 'Essays');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        if($this->request->ajax()){
            $date = $this->request->input('date');
            return view('super.essays.limited')->with($view_arr);
        }

        return view('super.essays.show')->with($view_arr);
    }

    public function stash()
    {

    }
}
