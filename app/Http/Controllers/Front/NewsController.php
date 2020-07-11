<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Model\News\News;
use App\Model\News\NewsShow;
use App\Model\Comment\Comment;
use App\Model\Question\Question;
use App\Model\News\NewsScore;
use App\Model\Topic;

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
        $now = Carbon::now();

        $start_month = $now->startofMonth()->format('Y-m-d');
        $end_month = $now->endofMonth()->format('Y-m-d');

        $view_arr['latest_dt'] = NewsShow::select(['show_on'])
                        ->where('locked', 0)
                        ->orderBy('show_on', 'DESC')
                        ->pluck('show_on')
                        ->first();

        $view_arr['month_dates'] = NewsShow::select(['show_on'])
                    ->where('locked', 0)
                    ->orderBy('show_on', 'ASC')
                    ->pluck('show_on');


        return view('front.news.list')->with($view_arr);
    }

    public function date($dt)
    {
        $view_arr = array();

        $cd = \DateTime::createFromFormat('Y-m-d', $dt);
        if(!$cd || ($cd->format('Y-m-d') != $dt)){
            $this->request->session()->flash('msgs', array('Invalid Date specified'));
            return redirect()->route('front::news', ['action' => 'list']);
        }

        $news_show = NewsShow::with('news')->where('show_on', $dt)->where('locked',0)->first();
        if(is_null($news_show)){
            $this->request->session()->flash('msgs', array('Inactive Date'));
            return redirect()->route('front::news', ['action' => 'list']);
        }


        $topic_arr = [];
        $topic = new Topic;
        foreach ($news_show->news as $current_news) {
            $topic_arr[$current_news->topic_id] = $topic->getTopicHier($current_news->sub, $current_news->topic_id);
        }

        $questions = Question::select('id', 'question', 'option_a', 'option_b', 'option_c', 'option_d')
                                    ->where('show_on', $dt)
                                    ->where('type', 1)      // config('aspirant.question.types.front')
                                    ->where('locked',0)
                                    ->get();

        $news_stat = NewsScore::select(\DB::raw("count(id) as total_users"), \DB::raw("avg(score) as avg"))->where('news_show_on', $dt)->first();

        $avg = $total_users = $user_score = 0;
        if(\Gate::allows('prelim-user')){
            $account_id = \UserHelper::getAccountId();
            $us = NewsScore::select('score')->where('account_id', $account_id)->where('news_show_on', $dt)->first();
            if(!empty($us)){
                $user_score = $us->score;
            }
        }        

        if(!empty($news_stat)){
            $avg = round((float) $news_stat->avg,2, PHP_ROUND_HALF_UP);
            $total_users = $news_stat->total_users;
        }

        $comments = new Comment;
        $path = $this->request->path();
        $page_comments = $comments->pageComments($path);

        $view_arr['news_show'] = $news_show;
        $view_arr['avg'] = $avg;
        $view_arr['topic_arr'] = $topic_arr;
        $view_arr['questions'] = $questions;
        $view_arr['page_comments'] = $page_comments;
        $view_arr['total_users'] = $total_users;
        $view_arr['user_score'] = $user_score;
        
        $view_arr['date'] = $cd;

        return view('front.news.date')->with($view_arr);
    }

    public function show($link)
    {
        $view_arr['news'] = News::where('news_link', $link)->first();
        if(empty($view_arr['news'])){
            abort(404, 'News not found');
        }
        return view('front.news.show')->with($view_arr);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'month_events':
                    $start_date = $this->request->input('sdt');
                    $end_date = $this->request->input('edt');

                    $dates = NewsShow::select(['show_on'])
                                    ->where('locked', 0)
                                    ->whereBetween('show_on', [$start_date, $end_date])
                                    ->orderBy('show_on', 'ASC')
                                    ->get();
                    $dt_arr = [];
                    foreach ($dates as $dt) {
                        $dt_arr[] = ['date' => $dt->show_on];
                    }          
                    return response()->json($dt_arr);
                    break;
                case 'news_questions':
                    $date = $this->request->input('date');
                    $d = \DateTime::createFromFormat('Y-m-d', $date);
                    if(!$d || ($d->format('Y-m-d') != $date)){
                        return response()->json(['message' => 'Invalid date'], 403);
                    }

                    $ans = $this->request->input('ans');
                    $ans_arr = json_decode($ans, true);

                    if(!is_array($ans_arr) || empty($ans_arr)){
                        return response()->json(['message' => 'Error in answers'], 403);
                    }

                    $questions = Question::select('id', 'answer', 'explanation')
                                    ->where('show_on', $date)
                                    ->where('type', 1)      // config('aspirant.question.types.front')
                                    ->where('locked',0)
                                    ->get();

                    $ques_ans = [];
                    foreach ($questions as $ques) {
                        $ques_ans[$ques->id] = [
                            'ans' => $ques->answer,
                            'exp' => json_encode($ques->explanation)
                        ];
                    }

                    $score = 0;
                    foreach ($ans_arr as $qid => $uans) {
                        if(array_key_exists($qid, $ques_ans) && in_array($uans, ["a", "b", "c", "d"])){
                            if(strcasecmp($ques_ans[$qid]['ans'],$uans) == 0){
                                $score += 2;
                            } else{
                                $score -= 0.66;
                            }
                        }
                    }
                    $score = round((float) $score,2, PHP_ROUND_HALF_UP);

                    if(\Gate::allows('prelim-user')){
                        $account_id = \UserHelper::getAccountId();
                        $news_score = NewsScore::where('account_id', $account_id)->where('news_show_on', $date)->first();
                        if(empty($news_score)){
                            $ust = 1;
                            
                            $ns = new NewsScore;
                            $ns->account_id = $account_id;
                            $ns->news_show_on = $date;
                            $ns->score = $score;
                            $ns->save();
                        } else{
                            $ust = 2;                       
                        }
                    } else{
                        $ust = 3;
                    }

                    return response()->json([
                        'ust' => $ust,
                        'score' => $score,
                        'ques_ans' => $ques_ans
                    ]);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
