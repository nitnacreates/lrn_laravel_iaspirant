<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Model\Prelim\PrelimRoster;
use App\Model\Prelim\PrelimQuestion;

class PrelimController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function schedule()
    {
        view()->share('page', 'prelim_schedule');
        view()->share('pagetitle', 'Prelim Schedule');
        
        $view_arr = array();
        $view_arr['rosters'] = PrelimRoster::all();
        return view('super.prelim.schedule')->with($view_arr);
    }

    public function pariksha()
    {
        // test checks
        $prelim_id = $this->request->input('pid',0);
        $data = ['pid' => $prelim_id];
        $validator = Validator::make($data, [
            'pid' => 'required|integer|min:1|max:40'
        ],[
                'id.min' => 'Test id is missing',
                'id.max' => 'Invalid Test id provided',
        ]);
        if($validator->fails()){
            return redirect()->route('super::prelim', ['action' => 'schedule'])->withErrors($validator);
        }

        view()->share('page', 'prelim_pariksha');
        view()->share('pagetitle', 'Prelim Pariksha');
        $view_arr = [
            'pid' => $prelim_id
        ];

        $view_arr['questions'] = PrelimQuestion::where('prelim_roster_id', $prelim_id)->orderBy('display_id','ASC')->get();
        
        return view('super.prelim.pariksha')->with($view_arr);
    }

    public function pariksha_stash()
    {
        // test checks
        $prelim_id = $this->request->input('pid',0);
        $pre_ques_id = $this->request->input('qid',0);
        $data = ['pid' => $prelim_id, 'qid' => $pre_ques_id];
        $validator = Validator::make($data, [
            'pid' => 'required|integer|min:1|max:40',
            'qid' => 'nullable|integer'
        ],[
                'id.min' => 'Test id is missing',
                'id.max' => 'Invalid Test id provided',
        ]);
        if($validator->fails()){
            return redirect()->route('super::prelim', ['action' => 'schedule'])->withErrors($validator);
        }

        // init
        view()->share('page', 'prelim_stash');
        view()->share('pagetitle', 'Prelim Stash');

        $params = $this->request->all();
        $view_arr = array();

        // post
        if ($this->request->isMethod('post')) {
            $this->validate($this->request, [
                'sub' => 'required|string',
                'topic_id' => 'required|integer|min:1',
                'ques_ref_id' => 'nullable|integer',
                'weightage' => 'required|integer',
                'question' => 'required',
                'option_a' => 'required',
                'option_b' => 'required',
                'option_c' => 'required',
                'option_d' => 'required',
                'answer' => 'required|in:a,b,c,d',
                'explanation' => 'required'
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            // TODO: check title against subject
            $sub = $params['sub'];
            if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
                $this->request->session()->flash('msgs', array('Subject does not exist'));
                return redirect()->route('super::question', ['action' => 'show']);  
            }

            $msg = 'There was an error';
            $msg_type = 'added';

            $pre_ques_obj = PrelimQuestion::firstOrNew(['id' => $pre_ques_id]);
            $msg_type = $pre_ques_obj->exists ? 'updated' : 'added';

            $pre_ques_obj->prelim_roster_id = $params['pid'];
            $pre_ques_obj->display_id = rand(1,100);
            $pre_ques_obj->sub = $params['sub'];
            $pre_ques_obj->topic_id = $params['topic_id'];
            $pre_ques_obj->question = $params['question'];
            $pre_ques_obj->option_a = $params['option_a'];
            $pre_ques_obj->option_b = $params['option_b'];
            $pre_ques_obj->option_c = $params['option_c'];
            $pre_ques_obj->option_d = $params['option_d'];
            $pre_ques_obj->answer = $params['answer'];
            $pre_ques_obj->explanation = $params['explanation'];
            $pre_ques_obj->secret_text = $params['secret_text'];
            $pre_ques_obj->ques_ref_id = $params['ques_ref_id'];
            $pre_ques_obj->weightage = $params['weightage'];
            $pre_ques_obj->save();

            $this->request->session()->flash('msgs', array('Question '. $pre_ques_obj->id .' successfully ' . $msg_type));
            return redirect()->route('super::prelim', ['action' => 'pariksha', 'pid' => $pre_ques_obj->prelim_roster_id]); 
        }

        // view
        if($pre_ques_id){
            $pre_ques = PrelimQuestion::find($pre_ques_id);
            if(empty($pre_ques)){
                $this->request->session()->flash('msgs', array('question does not exist'));
                return redirect()->route('super::prelim', ['action' => 'pariksha', 'pid' => $prelim_id]);  
            }
            $view_arr['pre_ques'] = $pre_ques;
        }
        
        $view_arr['mode'] = $pre_ques_id ? 'edit':'add';
        $view_arr['pid'] = $prelim_id;
        $view_arr['picker_url'] = route('super::media', ['action' => 'picker']);
        
        return view('super.prelim.pariksha_stash')->with($view_arr);
    }

    public function pariksha_attempts()
    {
        view()->share('page', 'prelim_attempts');
        view()->share('pagetitle', 'Prelim Attempts');

        $view_arr = array();
        return view('super.prelim.pariksha_attempts')->with($view_arr);
    }

    public function pariksha_attempts_show()
    {
        view()->share('page', 'prelim_attempts');
        view()->share('pagetitle', 'Prelim Attempts');

        $view_arr = array();
        return view('super.prelim.pariksha_attempts_show')->with($view_arr);
    }
}
