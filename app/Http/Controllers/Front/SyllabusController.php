<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Topic;

class SyllabusController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'syllabus');
        view()->share('pagetitle', 'Syllabus');
        $this->request = $request;
    }

    public function picker()
    {
        $sub = $this->request->input('sub');
        if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
            exit('No syllabus selected');
        }
        $topics = new Topic;
        $topics_arr = $topics->structuredSubject($sub);

        $view_arr = array(
            'sub' => $sub,
            'sub_title' => $sub_title,
            'topics_arr' => $topics_arr
        );

        return view('front.syllabus.picker')->with($view_arr);
    }
}
