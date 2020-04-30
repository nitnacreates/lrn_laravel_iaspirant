<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'question_bank');
        view()->share('pagetitle', 'Question Bank');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        return view('super.question.show')->with($view_arr);
    }

    public function stash()
    {
        $view_arr = array();
        return view('super.question.stash')->with($view_arr);
    }
}
