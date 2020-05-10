<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
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
            $view_arr['news'] = News::where('show_on', $date)->get();

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
            $subject_maps = array_keys(config('aspirant.subject_map'));
            $this->validate($this->request, [
                'news_title' => 'required',
                'locked' => 'boolean',
                'news_link' => ['required', 'regex:/^[a-z0-9-]*$/', Rule::unique('news', 'news_link')->ignore($news_id)],
                'meta_key' => 'nullable',
                'meta_descr' => 'nullable',
                'sub' => ['required',Rule::in($subject_maps)],
                'topic_id' => 'required|integer|min:1',
                'category' => 'required|in:p,m,b',
                'type' => 'required|in:dn,ed',
                'weightage' => 'required|integer',
                'show_on' => 'required|date_format:Y-m-d',
                'news_content' => 'required',
                'self_note' => 'nullable'
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            $msg = 'There was an error';

            $news_obj = News::firstOrNew(['id' => $news_id]);
            $msg_type = $news_obj->exists ? 'updated' : 'added';

            $news_obj->news_title = $params['news_title'];
            $news_obj->locked = $params['locked'];
            $news_obj->news_link = $params['news_link'];
            $news_obj->meta_key = $params['meta_key'];
            $news_obj->meta_descr = $params['meta_descr'];
            $news_obj->sub = $params['sub'];
            $news_obj->topic_id = $params['topic_id'];
            $news_obj->category = $params['category'];
            $news_obj->type = $params['type'];
            $news_obj->weightage = $params['weightage'];
            $news_obj->show_on = $params['show_on'];
            $news_obj->news_content = $params['news_content'];
            $news_obj->self_note = $params['self_note'];
            $news_obj->save();

            $this->request->session()->flash('msgs', array('News successfully ' . $msg_type));
            return redirect()->route('super::news', ['action' => 'show', 'date' => $news_obj->show_on]);  
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
                case 'news_link':
                    $id = $this->request->input('id');
                    $link = $this->request->input('link');
                    $resp = preg_match('/^[a-z0-9-]*$/', $link);

                    if($resp){
                        $check = News::where('news_link', $link)->where('id', ['neq' => $id])->first();
                        return empty($check) ? response()->json(['message' => 'Link not found']) : response()->json(['message' => 'An error occurred'], 404);
                    }
                    
                    return response()->json(['message' => 'An error occurred'], 404);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
