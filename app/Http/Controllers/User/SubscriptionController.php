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
            'plan' => 'required|in:free,premium,pro'
        ]);

        if ($validator->fails()) {
            $this->request->session()->flash('msgs', ['Plan expired or does not exist']);
            return redirect()->route('user::subscription', ['action' => 'show']);  
        }

        if($params['plan'] == 'free'){
            $this->request->session()->flash('msgs', ['Free is already part of your subscription']);
            return redirect()->route('user::subscription', ['action' => 'show']);
        }else{
            switch ($params['plan']) {
                case 'premium': 
                    $view_arr['plan'] = 'Premium User';
                    $view_arr['price'] = 4999.00;
                    break;
                case 'pro': 
                    $view_arr['plan'] = 'Pro User';
                    $view_arr['price'] = 9999.00; 
                    break;
                default:break;
            }
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
