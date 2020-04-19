<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index()
    {
        return view('front.home.index');
    }
}
