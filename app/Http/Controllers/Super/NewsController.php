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
            $view_arr['news'] = News::select(['id', 'news_title', 'sub', 'topic_id', 'category', 'type', 'weightage', 'visible_from', 'locked', \DB::raw("CAST(`created_at` AS DATE) AS created_date")])
                ->where('created_at', 'like', $date.'%')
                ->get();

            return view('super.news.limited')->with($view_arr);
        }

        return view('super.news.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $view_arr = array();
        $news_id = isset($params['id']) ? intval(trim($params['id'])) : 0;

        if ($this->request->isMethod('post')) {
            $this->validate($this->request, [
                'news_title' => 'required',
                'locked' => 'boolean',
                'sub' => 'required|string',
                'topic_id' => 'required|integer|min:1',
                'category' => 'required|in:p,m,b',
                'type' => 'required|in:dn,ed',
                'visible_from' => 'required|date_format:Y-m-d',
                'weightage' => 'required|integer',
                // 'news_content' => 'required|min:500',
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            // TODO: check title against subject
            $sub = $params['sub'];
            if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
                $this->request->session()->flash('msgs', array('Subject does not exist'));
                return redirect()->route('super::news', ['action' => 'show']);
            }

            $msg = 'There was an error';

            $news_obj = News::firstOrNew(['id' => $news_id]);
            $news_obj->news_title = $params['news_title'];
            $news_obj->locked = $params['locked'];
            $news_obj->sub = $params['sub'];
            $news_obj->topic_id = $params['topic_id'];
            $news_obj->category = $params['category'];
            $news_obj->type = $params['type'];
            $news_obj->visible_from = $params['visible_from'];
            $news_obj->weightage = $params['weightage'];
            $news_obj->news_content = $params['news_content'];
            $news_obj->save();

            $front_date = \SuperHelper::transformDate('Y-m-d H:i:s','Y-m-d',$news_obj->created_at);
            $this->request->session()->flash('msgs', array('News successfully ' . ($news_obj->exists ? 'updated' : 'added')));
            return redirect()->route('super::news', ['action' => 'show', 'date' => $front_date]);  
        }

        // show
        $mode = 'add';
        if($news_id){
            $mode = 'edit';
            $view_arr['news'] = News::find($news_id);
            if(empty($view_arr['news'])){
                $this->request->session()->flash('msgs', array('News does not exist'));
                return redirect()->route('super::news', ['action' => 'show','date' => date('Y-m-d')]);
            }

        }
        $view_arr['mode'] = $mode;
        $view_arr['picker_url'] = route('super::media', ['action' => 'picker']);
        return view('super.news.stash')->with($view_arr);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'month_news':
                    $start_date = $this->request->input('sdt');
                    $start_date =\SuperHelper::transformDate('Y-m-d','Y-m-d H:i:s', $start_date);

                    $end_date = $this->request->input('edt');
                    $end_date =\SuperHelper::transformDate('Y-m-d','Y-m-d H:i:s', $end_date);

                    $dates = News::select([\DB::raw("DATE(`created_at`) AS dater")])
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->groupBy(\DB::raw("DATE(`created_at`)"))
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
