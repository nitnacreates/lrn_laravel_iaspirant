<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Model\News\News;
use App\Model\News\NewsShow;

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
            $view_arr['news_show'] = NewsShow::with('news')->where('show_on', $date)->first();

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
                'news_link' => ['required', 'regex:/^[a-z0-9-]*$/', Rule::unique('news', 'news_link')->ignore($news_id)],
                'meta_key' => 'nullable',
                'meta_descr' => 'nullable',
                'sub' => ['required',Rule::in($subject_maps)],
                'topic_id' => 'required|integer|min:1',
                'category' => 'required|in:p,m,b',
                'type' => 'required|in:dn,ed',
                'weightage' => 'required|integer',
                'news_content' => 'required',
                'self_note' => 'nullable',
                'show_on' => 'required|array|min:1'
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            $msg = 'There was an error';

            $news_obj = News::firstOrNew(['id' => $news_id]);
            $msg_type = $news_obj->exists ? 'updated' : 'added';
            $news_obj->news_title = $params['news_title'];
            $news_obj->news_link = $params['news_link'];
            $news_obj->meta_key = $params['meta_key'];
            $news_obj->meta_descr = $params['meta_descr'];
            $news_obj->sub = $params['sub'];
            $news_obj->topic_id = $params['topic_id'];
            $news_obj->category = $params['category'];
            $news_obj->type = $params['type'];
            $news_obj->weightage = $params['weightage'];
            $news_obj->news_content = $params['news_content'];
            $news_obj->self_note = $params['self_note'];
            $news_obj->save();

            // show, at least one has to exist
            $valid_dts = [];
            $news_show_attach = [];

            foreach ($params['show_on'] as $key => $dt) {
                if(\FrontHelper::validateDate($dt, 'Y-m-d')){
                    if(!in_array($dt, $valid_dts)){
                        array_push($valid_dts, $dt);
                        $news_show = NewsShow::where('show_on', $dt)->first();
                        if($news_show === null){
                            $news_show = new NewsShow;
                            $news_show->show_on = $dt;
                            $news_show->locked = '1';
                            $news_show->save();
                        }
                        $news_show_attach[] = $news_show->id;
                    }
                }
            }

            $display_date = reset($valid_dts);
            // reattach
            if(!empty($news_show_attach)){
                
                // current
                $attached_rows = $news_obj->news_shows()->get();
                $exist_dt = [];
                foreach ($attached_rows as $row) {
                    $exist_dt[$row->id] = $row->show_on->format('Y-m-d');
                }

                $detach_dates = array_diff($exist_dt, $valid_dts);
                if(!empty($detach_dates)){
                    $news_obj->news_shows()->detach(array_keys($detach_dates));
                }

                $attach_dates = array_diff($valid_dts, $exist_dt);
                if(!empty($attach_dates)){
                    $display_date = end($attach_dates);
                    $attach__ids = NewsShow::whereIn('show_on', $attach_dates)->pluck('id');
                    $news_obj->news_shows()->attach($attach__ids);
                }
            }

            //remove orphan dates
            NewsShow::doesntHave('News')->delete();

            $this->request->session()->flash('msgs', array('News successfully ' . $msg_type));
            return redirect()->route('super::news', ['action' => 'show', 'date' => $display_date]);  
        }

        // show
        $mode = 'add';
        if($news_id){
            $mode = 'edit';
            $view_arr['news'] = News::with(['news_shows' =>function($q){
                $q->orderBy('show_on','DESC');
            }])->find($news_id);

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

                    $dates = NewsShow::select(['show_on'])
                                    ->whereBetween('show_on', [$start_date, $end_date])
                                    ->orderBy('show_on', 'ASC')
                                    ->get();
                    $dt_arr = [];
                    foreach ($dates as $dt) {
                        $dt_arr[] = ['date' => $dt->show_on];
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
                case 'date_lock':
                    $date = $this->request->input('date');
                    $act = $this->request->input('act');
                    NewsShow::where('show_on', $date)->first()->update(['locked' => $act]);
                    
                    return response()->json();
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
