<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Model\Essay\Essay;

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

        echo $dt;
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
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }

}
