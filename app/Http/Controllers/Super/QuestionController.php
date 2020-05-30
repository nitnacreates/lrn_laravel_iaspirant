<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Question\Question;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'question_bank');
        view()->share('pagetitle', 'Question Bank');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = [
            'current' => []
        ];

        $questions = Question::select(['id', 'type', 'sub', 'topic_id', 'account_id', 'user_type', 'question', 'locked', 'show_on']);
        if ($this->request->isMethod('get')){
            $question_types = array_keys(config('aspirant.question.types.front') + config('aspirant.question.types.super'));
            
            // dd($this->request->all());

            $this->validate($this->request, [
                'type' => ['nullable',Rule::in($question_types)],
                'sub' => 'nullable|string',
                'user_type' => 'nullable|in:s,n',
                'question' => 'nullable',
                'explanation' => 'nullable'
            ]);
            
            foreach ($this->request->all() as $param => $val) {
                if(empty($val)){
                    $this->request->offsetUnset($param);
                    continue;
                }

                switch ($param) {
                    case 'type':
                        $questions->where('type',strip_tags($val));
                        break;
                    case 'sub':
                        $questions->where('sub',strip_tags($val));
                        break;
                    case 'user_type':
                        $questions->where('user_type',strip_tags($val));
                        break;
                    case 'question':
                        $val = strip_tags($val);
                        $questions->where('question', 'like', "%{$val}%");
                        break;
                    case 'explanation':
                        $val = strip_tags($val);
                        $questions->where('explanation', 'like', "%{$val}%");
                        break;
                    default:
                        # code...
                        break;
                }
            }

            $view_arr['current'] = $this->request->all();
        }

        $questions = $questions->orderBy('show_on' ,'DESC')
            ->simplePaginate(config('aspirant.page_limit'));
        
        $view_arr['questions'] = $questions;
        return view('super.question.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $view_arr = array();
        $question_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        

        if ($this->request->isMethod('post')) {
            $question_types = array_keys(config('aspirant.question.types.front') + config('aspirant.question.types.super'));
            $this->validate($this->request, [
                'type' => ['required',Rule::in($question_types)],
                'locked' => 'required|boolean',
                'sub' => 'required|string',
                'topic_id' => 'required|integer|min:1',
                'question' => 'required',
                'option_a' => 'required',
                'option_b' => 'required',
                'option_c' => 'required',
                'option_d' => 'required',
                'answer' => 'required|in:a,b,c,d',
                'explanation' => 'required',
                'show_on' => 'required|date_format:Y-m-d'
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

            $ques_obj = Question::firstOrNew(['id' => $question_id]);
            $ques_obj->type = $params['type'];
            $ques_obj->sub = $params['sub'];
            $ques_obj->topic_id = $params['topic_id'];
            if($ques_obj->exists){
                $msg_type = 'updated';
            } else{
                $account_id = \SuperHelper::getAccountId();
                $ques_obj->account_id = $account_id;
                $ques_obj->user_type = 's'; // only super can add from super controller
            }
            $ques_obj->question = $params['question'];
            $ques_obj->option_a = $params['option_a'];
            $ques_obj->option_b = $params['option_b'];
            $ques_obj->option_c = $params['option_c'];
            $ques_obj->option_d = $params['option_d'];
            $ques_obj->answer = $params['answer'];
            $ques_obj->explanation = $params['explanation'];
            $ques_obj->secret_text = array_key_exists('secret_text', $params) ? $params['secret_text'] : '';
            $ques_obj->locked = $params['locked'];
            $ques_obj->show_on = $params['show_on'];
            $ques_obj->save();

            $this->request->session()->flash('msgs', array('Question '. $ques_obj->id .' successfully ' . $msg_type));
            return redirect()->route('super::question', ['action' => 'show']); 
        }

        // view
        if($question_id){
            $question = Question::find($question_id);
            if(empty($question)){
                $this->request->session()->flash('msgs', array('question does not exist'));
                return redirect()->route('super::question', ['action' => 'show']);  
            }
            $view_arr['question'] = $question;
        }

        $view_arr['mode'] = $question_id ? 'edit':'add';
        $view_arr['picker_url'] = route('super::media', ['action' => 'picker']);
        return view('super.question.stash')->with($view_arr);
    }
}
