<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Prelim\PrelimRoster;

class PrelimController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'prelim_schedule');
        view()->share('pagetitle', 'Prelim Schedule');
        $this->request = $request;
    }

    public function schedule()
    {
        $view_arr = array();
        $view_arr['rosters'] = PrelimRoster::all();
        return view('super.prelim.schedule')->with($view_arr);
    }

    public function pariksha()
    {
        $view_arr = array();
        return view('super.prelim.pariksha')->with($view_arr);
    }

    public function parikshaUttar()
    {
        $view_arr = array();
        return view('super.prelim.pariksha_uttar')->with($view_arr);
    }

    public function parikshaStash()
    {
        $view_arr = array();
        return view('super.prelim.pariksha_stash')->with($view_arr);
    }
}
