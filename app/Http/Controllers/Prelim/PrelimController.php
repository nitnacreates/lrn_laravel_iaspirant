<?php

namespace App\Http\Controllers\Prelim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use App\Model\Prelim\PrelimRoster;
use App\Model\Prelim\PrelimResponse;

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
        $view_arr['user_test_history'] = PrelimResponse::distinct()->get(['prelim_roster_id'])->where('user_id', $user_id)->pluck('prelim_roster_id');
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
                    $completed_at = $started_at->add(CarbonInterval::make('2h'));
                    PrelimResponse::where('id', $response->id)->update(['completed_at', $completed_at]);
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
        return view('prelim.show')->with($view_arr);
    }

    public function show()
    {
        $view_arr = [];
        return view('prelim.show')->with($view_arr);
    }

}
