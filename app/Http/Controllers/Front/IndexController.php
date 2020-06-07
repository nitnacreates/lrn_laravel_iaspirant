<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Essay\Essay;
use App\Model\Question\Question;

class IndexController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'home');
        view()->share('pagetitle', 'Home');
        $this->request = $request;
    }

    public function index()
    {
        $view_arr = array();
        $view_arr['essay'] = Essay::select('title', 'word_limit', 'sub', 'weightage', 'created_on')->where('locked', 0)->orderBy('created_on', 'DESC')->first();
        $view_arr['question'] = Question::select('sub', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'show_on')->where('type', 1)->where('locked',0)->orderBy('show_on', 'DESC')->first();

        return view('front.home.index')->with($view_arr);
    }
}
