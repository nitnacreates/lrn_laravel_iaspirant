<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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

    public function show($sub)
    {
        $view_arr = array();

        if(!empty($sub) && !($sub_title = \SubjectHelper::checkSubTitle($sub))){
            return redirect()->route('front::syllabus-show', ['param' => 'polity']);
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

    /*
    * TODO - Add pagination if list increases
    */
    public function news($sub)
    {
        $view_arr = array();
        $topic = $this->request->input('topic','');
        // validation
        $subject_maps = array_keys(config('aspirant.subject_map'));
        $data = [
            'sub' => $sub,
            'topic' => $topic
        ];
        $validator = Validator::make($data, [
            'sub' => ['required',Rule::in($subject_maps)],
            'topic' =>  ['required', 'integer', 'min:1', Rule::exists('topics', 'id')->where(function($query) use($sub) {
                    $query->where('sub',$sub);
            })]
        ],[
            'sub.in' => 'Invalid subject',
            'topic_id.min' => 'Topic is missing',
            'topic_id.exists' => 'Subject Topic mismatch',
        ]);
        if($validator->fails()){
            return redirect()->route('front::syllabus-show', ['param' => 'polity'])->withErrors($validator);
        }

        $view_arr['sub'] = $sub;

        $view_arr['news'] = News::select('id', 'news_title', 'news_link', 'news_content', 'sub', 'topic_id', 'category', 'type', 'weightage', 'show_on', 'created_at', 'updated_at')->where('sub', $sub)->where('topic_id',$topic)->where('locked',0)->get();
        return view('front.syllabus.news')->with($view_arr);
    }

    public function questions($sub)
    {
        $view_arr = array();

        if(!empty($sub) && !($sub_title = \SubjectHelper::checkSubTitle($sub))){
            return redirect()->route('front::syllabus-show', ['param' => 'polity']);
        }
        $view_arr['sub'] = $sub;
        
        return view('front.syllabus.questions')->with($view_arr);
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
