<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Essay\Essay;
use App\Model\Essay\EssayResponse;

class EssaysController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'essays');
        view()->share('pagetitle', 'Essays');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        if($this->request->ajax()){
            $date = $this->request->input('date');
            $view_arr['essays'] = Essay::select(['id', 'title', 'word_limit', 'sub', 'topic_id', 'weightage', 'locked', 'created_on'])
                ->where('created_on', $date)
                ->get();
            return view('super.essays.limited')->with($view_arr);
        }

        return view('super.essays.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $view_arr = array();
        $essay_id = isset($params['id']) ? intval(trim($params['id'])) : 0;

        if ($this->request->isMethod('post')) {
            $this->validate($this->request, [
                'title' => 'required',
                'sub' => 'required|string',
                'topic_id' => 'required|integer|min:1',
                'word_limit' => 'required|integer',
                'weightage' => 'required|integer',
                'created_on' => 'required|date_format:Y-m-d',
                'locked' => 'required|boolean',
                // 'framework' => 'nullable|min:50',
                // 'references' => 'required|min:500',
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            $sub = $params['sub'];
            if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
                $this->request->session()->flash('msgs', array('Subject does not exist'));
                return redirect()->route('super::theory', ['action' => 'show']);
            }

            $msg = 'There was an error';
            $msg_type = 'added';
            $essay_obj = Essay::firstOrNew(['id' => $essay_id]);
            if($essay_obj->exists) {
                $msg_type = 'updated';
            }
            $essay_obj->title = $params['title'];
            $essay_obj->framework = $params['framework'];
            $essay_obj->references = $params['references'];
            $essay_obj->word_limit = $params['word_limit'];
            $essay_obj->sub = $params['sub'];
            $essay_obj->topic_id = $params['topic_id'];
            $essay_obj->weightage = $params['weightage'];
            $essay_obj->locked = $params['locked'];
            $essay_obj->created_on = $params['created_on'];
            $essay_obj->save();

            $this->request->session()->flash('msgs', array('Essay successfully ' . $msg_type));
            return redirect()->route('super::essays', ['action' => 'show', 'date' => $essay_obj->created_on]); 
        }
        
        // view
        if($essay_id){
            $essay = Essay::find($essay_id);
            if(empty($essay)){
                $this->request->session()->flash('msgs', array('essay does not exist'));
                return redirect()->route('super::essays', ['action' => 'show']);  
            }
            $view_arr['essay'] = $essay;
        }

        $view_arr['mode'] = $essay_id ? 'edit':'add';
        $view_arr['picker_url'] = route('super::media', ['action' => 'picker']);
        return view('super.essays.stash')->with($view_arr);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'month_essays':
                    $start_date = $this->request->input('sdt');
                    $end_date = $this->request->input('edt');

                    $dates = Essay::select('created_on')
                                    ->whereBetween('created_on', [$start_date, $end_date])
                                    ->groupBy('created_on')
                                    ->get();
                    $dt_arr = [];
                    foreach ($dates as $dt) {
                        $dt_arr[] = ['date' => $dt->created_on];
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
