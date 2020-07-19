<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\User\UserKeep;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'user_subscription');
        view()->share('pagetitle', 'Subscription');
        $this->request = $request;
    }

    public function show()
    {
        $user_id = \UserHelper::getUserId();
        
        $view_arr['user'] = User::find($user_id);
        $view_arr['pagetitle'] = 'Subscription Plans';
        return view('user.subscription.show')->with($view_arr);
    }

    public function review()
    {
        $params = $this->request->all();

        $validator = Validator::make($params, [
            'plan' => 'required|in:premium,pro'
        ]);

        if ($validator->fails()) {
            $this->request->session()->flash('msgs', ['Plan expired or does not exist']);
            return redirect()->route('user::subscription', ['action' => 'show']);  
        }

        if($params['plan'] == 'free'){
            return redirect()->route('user::subscription', ['action' => 'show']);
        }

        $user_id = \UserHelper::getUserId();
        $view_arr['user'] = User::with('user_keep')->find($user_id);

        $view_arr['pagetitle'] = 'Subscription Review';
        return view('user.subscription.review')->with($view_arr);
    }

    public function ensue()
    {
        exit('susu');   
    }
}
