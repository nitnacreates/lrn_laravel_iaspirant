<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\News\News;

class NewsController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'news');
        view()->share('pagetitle', 'News');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        if($this->request->ajax()){

            $date = $this->request->input('date');
            $view_arr['news'] = News::select(['id', 'news_title', 'sub', 'topic_id', 'category', 'type', 'weightage', 'visible_from', 'locked'])->where('created_on', '=', $date)->get();

            return view('super.news.limited')->with($view_arr);
        }

        return view('super.news.show')->with($view_arr);
    }

    public function stash()
    {
       
    }
}
