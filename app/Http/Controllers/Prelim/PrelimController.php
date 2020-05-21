<?php

namespace App\Http\Controllers\Prelim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    
    public function prelude()
    {
        $view_arr = [];
        return view('prelim.prelude')->with($view_arr);
    }

    public function show()
    {
        $view_arr = [];
        return view('prelim.show')->with($view_arr);
    }
}
