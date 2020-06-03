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

    public function show()
    {
        $view_arr = array();
        $sub = $this->request->input('sub','');

        if(!empty($sub) && !($sub_title = \SubjectHelper::checkSubTitle($sub))){
            return redirect()->route('front::syllabus', ['action' => 'show']);
        }
        $view_arr['sub'] = $sub;

        $topics = Topic::where('sub','=',$sub)->orderBy('level','ASC')->orderBy('title','ASC')->get();

        $syll_arr = [];
        foreach ($topics as $topic) {
            $syll_arr[$topic->id] = [
                'title' => $topic->title,
                'about' => $topic->about,
                'category' => $topic->category,
                'level' => $topic->level,
                'parent' => [],
                'children' => []
            ];

            if($topic->parent){
                $syll_arr[$topic->id]['parent'] = array_merge($syll_arr[$topic->parent]['parent'], [$topic->parent]);
                array_push($syll_arr[$topic->parent]['children'], $topic->id);
            }
        }

        dd($syll_arr);

        return view('front.syllabus.show')->with($view_arr);
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
