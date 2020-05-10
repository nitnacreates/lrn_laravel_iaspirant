<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NewsController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'news');
        view()->share('pagetitle', 'News');
        $this->request = $request;
    }

    public function list()
    {
        $view_arr = array();
        return view('front.news.list')->with($view_arr);
    }
}
