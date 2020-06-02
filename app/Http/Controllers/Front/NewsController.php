<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Model\News\News;
use App\Model\Comment\Comment;
use App\Model\Question\Question;

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

        $view_arr['latest_dt'] = News::select(['show_on'])
                        ->where('locked', 0)
                        ->orderBy('show_on', 'DESC')
                        ->pluck('show_on')
                        ->first();

        $view_arr['month_dates'] = News::select(['show_on'])
                    ->whereBetween('show_on', [$start_month, $end_month])
                    ->where('locked', 0)
                    ->groupBy('show_on')
                    ->orderBy('show_on', 'DESC')
                    ->pluck('show_on');


        return view('front.news.list')->with($view_arr);
    }

    public function date($dt)
    {
        $view_arr = array();

        $comments = new Comment;
        $path = $this->request->path();
        $view_arr['page_comments'] = $comments->pageComments($path);

        $d = \DateTime::createFromFormat('Y-m-d', $dt);
        if(!$d || ($d->format('Y-m-d') != $dt)){
            abort(403, 'Date not found');
        }

        $view_arr['news'] = News::where('show_on', $dt)->where('locked',0)->get();
        $view_arr['questions'] = Question::select('id', 'question', 'option_a', 'option_b', 'option_c', 'option_d')
                                    ->where('show_on', $dt)
                                    ->where('type', 1)      // config('aspirant.question.types.front')
                                    ->where('locked',1)
                                    ->get();
        $view_arr['date'] = $dt;

        return view('front.news.date')->with($view_arr);
    }

    public function show($link)
    {
        $view_arr['news'] = News::where('news_link', $link)->where('locked',0)->first();
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

                    $dates = News::select(['show_on as dater'])
                                    ->whereBetween('show_on', [$start_date, $end_date])
                                    ->groupBy('show_on')
                                    ->get();
                    $dt_arr = [];
                    foreach ($dates as $dt) {
                        $dt_arr[] = ['date' => $dt->dater];
                    }          
                    return response()->json($dt_arr);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
