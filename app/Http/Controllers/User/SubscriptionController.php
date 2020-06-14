<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\User\UserKeep;

class SubscriptionController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'user_subscription');
        view()->share('pagetitle', 'User Subscription');
        $this->request = $request;
    }

    public function show()
    {
        $user_id = \UserHelper::getUserId();
        $view_arr['user'] = User::select('subscription')
                                ->with(['user_keep' => function($query){
                                    $query->select('transaction_data');
                                }])
                                ->find($user_id);

        return view('user.subscription.show')->with($view_arr);
    }
}
