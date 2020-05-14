<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use App\Model\Essay\Essay;
use App\Model\Essay\EssayResponse;


class EssaysController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'user-essays');
        view()->share('pagetitle', 'User Essay');
        $this->request = $request;
    }

    public function show()
    {


    }

    public function summon()
    {

        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'pick_essay':

                    $essay_id = $this->request->input('eid');
                    $essay = Essay::where('id', $essay_id)->where('locked', 0)->first();

                    if(!empty($essay)){

                        $account_id = \UserHelper::getAccountId();
                        $essay_response = EssayResponse::where('essay_id', $essay_id)->where('account_id', $account_id)->first();
                        
                        if(empty($essay_response)){
                            $essay_response = new EssayResponse;
                            $essay_response->essay_id = $essay_id;
                            $essay_response->account_id = $account_id;
                            $essay_response->locked = 1;
                            $essay_response->save();
                        }

                        if(is_null($essay_response->completed_on)){
                            $response_created = $essay_response->created_at;
                            $response_carb = Carbon::createFromFormat('Y-m-d H:i:s', $response_created);
                            
                            $now = Carbon::now();
                            $more_than_day = $now->diffInDays($response_carb->copy()); 
                            if($more_than_day){
                                // delete record if completed on is null and more than a day has passed
                                EssayResponse::destroy($essay_response->id);
                                $response_arr = [
                                    'time_left' => '00:00:00',
                                    'status' => 'expired',
                                    'message' => 'Timer has expired'
                                ];
                            } else{
                                $time_left = $response_carb->add('1 day')->diff($now)->format('%H:%I:%S');
                                $time_left = ($time_left == "00:00:00") ? "23:59:59" : $time_left;
                                $response_arr = [
                                    'time_left' => $time_left,
                                    'status' => 'progress',
                                    'message' => 'You have ' . $time_left . ' to complete essay'
                                ];
                            }
                        } else{
                            $response_created = Carbon::createFromFormat('Y-m-d H:i:s', $essay_response->created_at);
                            $completed_on = Carbon::createFromFormat('Y-m-d H:i:s', $essay_response->completed_on);

                            $comp_in = $completed_on->diff($response_created)->format('%H:%I:%S');

                            $response_arr = [
                                'time_left' => '00:00:00',
                                'status' => 'completed',
                                'message' => 'Essay completed in ' . $comp_in
                            ];
                        }

                        return response()->json($response_arr);
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
