<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\News\News;
use App\Model\Question\Question;
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

        $topics = new Topic;
        $topics_arr = $topics->structuredSubject($sub);
        $view_arr['topics_arr'] = $topics_arr;

        // news
        $news_arr = [];
        $news =  News::select('topic_id', \DB::raw("count(topic_id) as news_count"))
                        ->where('sub', $sub)
                        ->where('locked',0)
                        ->groupBy('topic_id')
                        ->get();
        foreach ($news as $curr_news) {
            $news_arr[$curr_news->topic_id] = $curr_news->news_count;
        }
        $view_arr['news_arr'] = $news_arr;

        // questions
        $ques_arr = [];
        $ques = Question::select('topic_id', \DB::raw("count(topic_id) as ques_count"))
                            ->where('type', 1)
                            ->where('locked',0)
                            ->groupBy('topic_id')
                            ->get();
        foreach ($ques as $curr_ques) {
            $ques_arr[$curr_ques->topic_id] = $curr_ques->ques_count;
        }
        $view_arr['ques_arr'] = $ques_arr;

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
