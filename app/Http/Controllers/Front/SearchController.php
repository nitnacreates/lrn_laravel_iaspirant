<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SearchController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'search');
        view()->share('pagetitle', 'Search');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        $query = $this->request->input('q','');
        $query = preg_replace("/[^a-zA-Z0-9\s]/", "", $query);
        
        if(\Gate::allows('full-user')){
            if(!empty($query) && (strlen($query) > 4)){
            
                $msg = "Total 5 results found";
            } else{
                $msg = "search needs to be at least 4 characters";
            }

        } else{
            $msg = "Searching is a " . config('aspirant.user_subscription')['3'] . " feature";
        }

        $view_arr['q'] = $query;
        $view_arr['msg'] = $msg;

        return view('front.search.show')->with($view_arr);
    }
}
