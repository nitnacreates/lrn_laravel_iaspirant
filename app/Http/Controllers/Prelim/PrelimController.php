<?php

namespace App\Http\Controllers\Prelim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use App\Model\Prelim\PrelimRoster;
use App\Model\Prelim\PrelimResponse;
use App\Model\Prelim\PrelimQuestion;

class PrelimController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'prelim');
        view()->share('pagetitle', 'Prelim');
        $this->request = $request;
    }

    public function schedule()
    {
        $view_arr = [];
        $user_id = \UserHelper::getUserId();

        $view_arr = array();
        $view_arr['rosters'] = PrelimRoster::all();
        return view('prelim.schedule')->with($view_arr);
    }
    
    /*
    ** Show attempt number on front
    * essay id 
    ** valiadate essay id if it is unlocked
    ** get response ids for current user where completed is null, order by attempt ASC. 
    ** close previous attempts if they have passed 2 hours
    ** if there is a test which is still under 2 hours, then redirect user to that test
    *
    * post
    ** make entry in started at for new response, redirect to exam
    */
    public function prelude()
    {
        $view_arr = [];
        $prelim_id = intval($this->request->input('id',0));
        if($prelim_id){
            $prelim = PrelimRoster::where('id', $prelim_id)->where('locked', 0)->first();
        }

        if(!$prelim_id || empty($prelim)){
            $this->request->session()->flash('msgs', array('This exam is not yet active'));
            return redirect()->route('prelim', ['action' => 'schedule']); 
        }
        
        $last_attempt = 0;
        $now = Carbon::now();
        $user_id = \UserHelper::getUserId();
        
        $prelim_response = PrelimResponse::where('user_id', $user_id)->orderBy('attempt', 'ASC')->get();

        foreach ($prelim_response as $response) {
            $last_attempt = $response->attempt;
            if(is_null($response->completed_at)){
                $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $response->started_at);
                $hours_passed = $started_at->diffInHours($now, false);

                if($hours_passed > 1){
                    $this->endTest($response->id, 1);
                } else{
                    return redirect()->route('prelim', ['action' => 'exam', 'roll' => $response->id]); 
                }
            }
        }

        $new_attempt = ++$last_attempt;
        if($this->request->isMethod('post')){
            $start_test = $this->request->input('start_test',0);
            if($start_test){
                $prelim_res = new PrelimResponse;
                $prelim_res->prelim_roster_id = $prelim_id;
                $prelim_res->user_id = $user_id;
                $prelim_res->attempt = $new_attempt;
                $prelim_res->started_at = $now->format('Y-m-d H:i:s');
                $prelim_res->save();

                return redirect()->route('prelim', ['action' => 'exam', 'roll' => $prelim_res->id]); 
            } else{
                $this->request->session()->flash('msgs', array('In correct channel used'));
                return redirect()->route('prelim', ['action' => 'schedule']); 
            }
        }

        $view_arr['id'] = $prelim_id;
        $view_arr['future_attempt'] = $new_attempt;
        return view('prelim.prelude')->with($view_arr);
    }

    /*
    * check if response id with user id exists and timer has not completed
    */
    public function exam()
    {
        $view_arr = [];
        $user_id = \UserHelper::getUserId();
        $roll_id = intval($this->request->input('roll',0));
        $now = Carbon::now();

        $proceed = 0;
        if($roll_id){
            $response = PrelimResponse::find($roll_id);
            if(!empty($response)){
                if($response->user_id == $user_id){
                    $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $response->started_at);
                    if(is_null($response->completed_at)){
                        $completed_at = $started_at->copy()->addHours(2);
                        
                        $hours_passed = $started_at->diffInHours($now, true);
                        if($hours_passed < 2){
                            $proceed = 1;       
                        } else{
                            $this->endTest($response->id, 1);
                        }
                    }
                }
            }
        }

        if(!$proceed){
            if($this->request->session()->has('msgs')){
                $this->request->session()->flash('msgs', $this->request->session()->get('msgs'));
            } else{
                $this->request->session()->flash('msgs', array('This exam is no longer in session'));
            }
            return redirect()->route('prelim', ['action' => 'schedule']); 
        }

        $exam_id = $response->prelim_roster_id;
        $questions = PrelimQuestion::select('id', 'display_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'weightage')
                        ->where('prelim_roster_id', $exam_id)
                        ->orderBy('display_id', 'asc')
                        ->get();

        $total_ques = $questions->count();
        if(!$total_ques){
            $this->request->session()->flash('msgs', array('Invalid exam session'));
            return redirect()->route('prelim', ['action' => 'schedule']);    
        }

        $view_arr['questions'] = $questions;
        $view_arr['roll_id'] = $roll_id;
        $view_arr['questions_count'] = $total_ques;
        $view_arr['response'] = $response;
        $view_arr['time_remaining'] = $completed_at->diff($now)->format('%H:%I:%S');

        return view('prelim.exam')->with($view_arr);
    }

    public function report()
    {
        $view_arr = [];
        $prelim_id = intval($this->request->input('id',0));
        if($prelim_id){
            $prelim = PrelimRoster::where('id', $prelim_id)->where('locked', 0)->first();
        }

        if(!$prelim_id || empty($prelim)){
            $this->request->session()->flash('msgs', array('This exam is not yet active'));
            return redirect()->route('prelim', ['action' => 'schedule']); 
        }

        $view_arr['toppers'] = PrelimResponse::with(['user_grab' => function($query){
                                     $query->select('id', 'name');
                                }])
                                ->select('id','user_id', 'marks')
                                ->where('attempt', 1)
                                ->where('marks', '>',0)
                                ->whereNotNull('completed_at')
                                ->orderBy('marks', 'DESC')
                                ->orderBy('correct_ans', 'DESC')
                                ->orderBy('wrong_ans', 'ASC')
                                ->limit(10)
                                ->get();               

        $user_id = \UserHelper::getUserId();
        $responses = PrelimResponse::where('user_id', $user_id)->whereNotNull('completed_at');

        $view_arr['responses'] = $responses;
        return view('prelim.report')->with($view_arr);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            $user_id = \UserHelper::getUserId();

            switch ($type) {
                case 'check_point':
                    $answers = $this->request->input('answers');
                    $roll_id = intval($this->request->input('roll_id',0));
                    $now = Carbon::now();

                    if($roll_id){
                        $response = PrelimResponse::find($roll_id);
                        if(!empty($response)){
                            if(
                                ($response->user_id == $user_id) &&
                                !empty($response->started_at) &&
                                is_null($response->completed_at)
                            ){
                                $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $response->started_at);
                                $hours_passed = $started_at->diffInHours($now, true);
                                if($hours_passed < 2){
                                    $response->live_response = $answers;  
                                    $response->save();
                                    return response()->json(['pl' => 0]);
                                } else{
                                    // reload page
                                    $this->endTest($response->id, 1);
                                    return response()->json(['message' => 'Test has expired', 'pl' => 1], 404);
                                }
                            } else{
                                return response()->json(['message' => 'Test not in session', 'pl' => 1], 404);
                            }
                        } else {
                            return response()->json(['message' => 'Test not found', 'pl' => 1], 404);    
                        }
                    } else {
                        return response()->json(['message' => 'Invalid test provided', 'pl' => 1], 404);
                    }

                    break;
                case 'end_roll':
                    $answers = $this->request->input('answers');
                    $roll_id = intval($this->request->input('roll_id',0));
                    $now = Carbon::now();

                    if($roll_id){
                        $response = PrelimResponse::find($roll_id);
                        if(!empty($response)){
                            if(
                                ($response->user_id == $user_id) &&
                                !empty($response->started_at) &&
                                is_null($response->completed_at)
                            ){
                                $response->live_response = $answers;  
                                $response->save();

                                $this->endTest($response->id, 0);
                                $this->request->session()->flash('msgs', array('Your test has ended'));
                                return response()->json(['message' => 'Your test has ended, loading results...', 'pl' => 1]);

                            } else{
                                return response()->json(['message' => 'Test not in session', 'pl' => 1], 404);
                            }
                        } else {
                            return response()->json(['message' => 'Test not found', 'pl' => 1], 404);    
                        }
                    } else {
                        return response()->json(['message' => 'Invalid test provided', 'pl' => 1], 404);
                    }

                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }

    /*
    * Ends test for current roll id
    * Does not do any validaton, so make sure roll id is pre validated
    */
    protected function endTest($roll_id, int $add_two = 0)
    {
        $response = PrelimResponse::find($roll_id);
        if(!empty($response)){
            $questions = PrelimQuestion::select('id', 'display_id', 'answer')
                            ->where('prelim_roster_id', $response->prelim_roster_id)
                            ->orderBy('display_id', 'asc')
                            ->get();

            $total_qs = $questions->count();

            $right_arr = [];
            foreach($questions as $ques){
                $right_arr[$ques->display_id] = $ques->answer;
            }

            $correct_ans = $wrong_ans = $wrong_marks = 0;

            $shots = (string) trim($response->live_response);
            $shot_arr = json_decode($shots, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                foreach($shot_arr as $did => $user_resp){
                    if (array_key_exists('c', $user_resp) && !empty($user_resp['c'])) {
                        if(strcasecmp($right_arr[$did], $user_resp['c']) == 0){
                            $correct_ans++;
                        } else{
                            $wrong_ans++;                            
                        }
                    }
                }

                if($wrong_ans){
                    $wrong_marks = round((float) $wrong_ans/3,2, PHP_ROUND_HALF_UP);
                }

                $marks = round((float) (($correct_ans - $wrong_marks)*2),2, PHP_ROUND_HALF_UP);

                if($add_two){
                    $started_at = Carbon::createFromFormat('Y-m-d H:i:s', $response->started_at);
                    $completed_at = $started_at->addHours(2)->format('Y-m-d H:i:s');
                } else{
                    $completed_at = Carbon::now()->format('Y-m-d H:i:s');
                }

                $response->final_response = $response->live_response;
                $response->correct_ans = $correct_ans;
                $response->wrong_ans = $wrong_ans;
                $response->marks = $marks;
                $response->completed_at = $completed_at;
            } else{
                // mail me for corrupt data
                $response->report = 1;
            }

            $response->save();
        }
        return true;
    }
}
