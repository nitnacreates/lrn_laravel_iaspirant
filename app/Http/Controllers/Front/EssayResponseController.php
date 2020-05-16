<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Model\Essay\Essay;

class EssayResponseController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'essay-response');
        view()->share('pagetitle', 'Essay Response');
        $this->request = $request;
    }

    public function show($user_name)
    {
        $view_arr = array();
        return view('front.essay_response.show')->with($view_arr);
    }

    public function create($user_name)
    {
        $params = $this->request->all();
        $essay_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        $view_arr = array();

        if($user_name != \UserHelper::getUserName()){
            $this->request->session()->flash('msgs', array('Invalid user'));
            return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show']);              
        }

        if($essay_id){
            
        }
        return view('front.essay_response.create')->with($view_arr);
    }

}
