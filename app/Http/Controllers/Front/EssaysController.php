<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Model\Essay\Essay;
use App\Model\Essay\EssayResponse;

class EssaysController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'essays');
        view()->share('pagetitle', 'Essay');
        $this->request = $request;
    }

    public function list()
    {
        $view_arr = array();
        $now = Carbon::now();

        $start_month = $now->startofMonth()->format('Y-m-d');
        $end_month = $now->endofMonth()->format('Y-m-d');

        $view_arr['latest_dt'] = Essay::select(['created_on'])
                        ->where('locked', 0)
                        ->orderBy('created_on', 'DESC')
                        ->limit(1)
                        ->pluck('created_on')
                        ->first();

        $view_arr['month_dates'] = Essay::select(['created_on'])
                    ->whereBetween('created_on', [$start_month, $end_month])
                    ->where('locked', 0)
                    ->groupBy('created_on')
                    ->orderBy('created_on', 'DESC')
                    ->pluck('created_on');


        return view('front.essays.list')->with($view_arr);
    }

    public function date($dt)
    {
        $view_arr = array();

        $d = \DateTime::createFromFormat('Y-m-d', $dt);
        if(!$d || ($d->format('Y-m-d') != $dt)){
            abort(403, 'Date not found');
        }

        $view_arr['essays'] = Essay::where('created_on', $dt)
                                ->where('locked',0)
                                ->with(['essay_response' => function($query) {
                                    $query->select('essay_responses.id', 'essay_responses.essay_id', 'essay_responses.account_id', 'essay_responses.completed_on', 'essay_responses.created_at', 'users.name')
                                        ->join('accounts', 'accounts.id', 'essay_responses.account_id')
                                        ->join('users', function ($join) {
                                            $join->on('users.id', '=', 'accounts.all_id')
                                                ->where('accounts.user_type','=', 'n');
                                        });
                                }])
                                ->get();
        $essay_responses = EssayResponse::select('essay_responses.*', 'users.name')
                            ->join('accounts', 'accounts.id', 'essay_responses.account_id')
                            ->join('users', function ($join) {
                                $join->on('users.id', '=', 'accounts.all_id')
                                    ->where('accounts.user_type','=', 'n');
                            })
                            ->whereIn('essay_responses.essay_id', function($query) use($dt) {
                                $query->select('id')->from('essays')->where('locked',0)->where('created_on', $dt);
                            })
                            ->whereNotNull('essay_responses.completed_on')
                            ->with('essay_reactions')
                            ->with(['grouped_essay_reactions' => function($query) {
                                $query->select('essay_response_id', 'type', \DB::raw("count(type) as type_counter"))
                                                    ->groupBy('essay_response_id')
                                                    ->groupBy('type');
                            }])
                            ->get();

        $view_arr['essay_responses'] = $essay_responses;

        $view_arr['account_id'] = \Gate::allows('pro-user') ? \UserHelper::getAccountId() : 0;
        return view('front.essays.date')->with($view_arr);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'month_events':
                    $start_date = $this->request->input('sdt');
                    $end_date = $this->request->input('edt');

                    $dates = Essay::select(['created_on as dater'])
                                    ->whereBetween('created_on', [$start_date, $end_date])
                                    ->where('locked', 0)
                                    ->groupBy('created_on')
                                    ->get();
                    $dt_arr = [];
                    foreach ($dates as $dt) {
                        $dt_arr[] = ['date' => $dt->dater];
                    }          
                    return response()->json($dt_arr);
                    break;
                case 'pick_essay':

                    if(\Gate::allows('pro-user')){
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
                                $response_countdown = \FrontHelper::countdownto24($essay_response->created_at);
                                if(!$response_countdown){
                                    // delete record if completed_on is null and more than a day has passed
                                    EssayResponse::destroy($essay_response->id);
                                    $response_arr = [
                                        'time_left' => '00:00:00',
                                        'status' => 'expired',
                                        'message' => 'Timer has expired. Pick again to reattempt'
                                    ];
                                } else{
                                    $response_arr = [
                                        'time_left' => $response_countdown,
                                        'status' => 'progress',
                                        'message' => 'You have ' . $response_countdown . ' to complete essay'
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
