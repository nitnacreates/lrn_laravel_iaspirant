<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

use App\User;
use App\Model\User\UserKeep;
use App\Model\User\UserSubscription;

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
            $amount_to_pay = 100000;
            switch ($params['plan']) {
                case 'premium': 
                    $plan = 'Premium User';
                    $plan_id = 15;
                    $amount_to_pay = 4999;
                    $view_arr['price'] = $amount_to_pay;
                    break;
                case 'pro': 
                    $plan = 'Pro User';
                    $plan_id = 20;
                    $amount_to_pay = 9999;
                    break;
                default:
                    $this->request->session()->flash('msgs', ['Subscription does not exist']);
                    return redirect()->route('user::subscription', ['action' => 'show']);
                break;
            }
        }

        // view arr
        $user_id = \UserHelper::getUserId();
        $user = User::with('user_keep')->find($user_id);

        $now = Carbon::now();
        $portal_id = $plan_id.$user_id.$now->format('YmdHis');

        // razorpay
        $api = new Api(config('aspirant.razorpay.api_key'), config('aspirant.razorpay.api_secret'));
        $razor_amount_topay = $amount_to_pay*100;

        $order  = $api->order->create([
            'receipt' => $portal_id, 
            'amount' => $razor_amount_topay, 
            'payment_capture' => 1,
            'currency' => 'INR'
        ]);
        $razorpayOrderId = $order['id'];

        // insert
        $user_subscription = new UserSubscription;
        $user_subscription->user_id = $user_id;
        $user_subscription->plan_id = $plan_id;
        $user_subscription->portal_id = $portal_id;
        $user_subscription->current_step = UserSubscription::STEP_BEFORE_PAYMENT;
        $user_subscription->tr_identifier = $razorpayOrderId;
        $user_subscription->payment_verified = 0;
        $user_subscription->portal_processed = 0;
        $user_subscription->save();

        $view_arr['razordata'] = [
            "key"               => config('aspirant.razorpay.api_key'),
            "amount"            => $razor_amount_topay,
            "name"              => $user->user_keep->first_name ." " . $user->user_keep->last_name,
            "description"       => $plan . " plan",
            // "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
            "prefill"           => [
                "name"              => $user->user_keep->first_name ." " . $user->user_keep->last_name,
                "email"             => $user->email,
                "contact"           => $user->user_keep->phone
            ],
            "theme"             => [
                "color"             => "#F37254"
            ],
            "order_id"          => $razorpayOrderId,
        ];

        $view_arr['pagetitle'] = 'Subscription Review';
        $view_arr['user'] = $user;
        $view_arr['razorpay_order_id'] = $razorpayOrderId;
        $view_arr['plan'] = $plan;
        $view_arr['plan_id'] = $plan_id;
        $view_arr['price'] = $amount_to_pay;
        return view('user.subscription.review')->with($view_arr);
    }

    public function ensue()
    {
        $params = $this->request->all();
        $validator = Validator::make($params, [
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required'
        ]);
        if($validator->fails()){
            $this->request->session()->flash('msgs', ['Invalid parameters passed. We have logged the error. Please drop a message below to help our team']);
            return redirect()->route('front::manifest', ['action' => 'contact']);
        }

        $api = new Api(config('aspirant.razorpay.api_key'), config('aspirant.razorpay.api_secret'));
        $verify = [
            'razorpay_order_id' => $params['razorpay_order_id'],
            'razorpay_payment_id' => $params['razorpay_payment_id'],
            'razorpay_signature' => $params['razorpay_signature']
        ];

        $success = true;
        try{
            $result = $api->utility->verifyPaymentSignature($verify);
            $msg = "Your order was successfull (subject to authorization) with order id - " . $params['razorpay_order_id'] . ". Please log out and log in again to view changes.";
        } catch(SignatureVerificationError $e){
            $success = false;
            $msg = "Sorry, your order could not be processed. Please retry again or drop a message to use with your order id - " . $params['razorpay_order_id'];
            // $msg = "There was an error - " .$e->getMessage();
        }

        $user_id = \UserHelper::getUserId();
        if($success){
            $user_subscription = UserSubscription::where('tr_identifier', $verify['razorpay_order_id'])
                                    ->where('user_id', $user_id)
                                    ->first();
            if(!empty($user_subscription)){
                $plan_id = $user_subscription->plan_id;
                
                // update user
                $user = User::find($user_id);
                $user->subscription = $plan_id;
                $user->save();

                // update subscription
                $now = Carbon::now();
                $user_subscription->current_step = UserSubscription::STEP_AFTER_PAYMENT;
                $user_subscription->tr_completed = $now->format('Y-m-d H:i:s');
                $user_subscription->tr_meta = serialize($verify);
                $user_subscription->portal_processed = 1;
                $user_subscription->save();
            }

        } else{
            // delete all plans of current user with current step 1, portal_processed 0
            $user_subscription = UserSubscription::where('user_id',$user_id)
                                    ->where('current_step',UserSubscription::STEP_BEFORE_PAYMENT)
                                    ->where('portal_processed',0)
                                    ->delete();
        }

        $view_arr['success'] = $success;
        $view_arr['msg'] = $msg;
        $view_arr['pagetitle'] = 'Subscription Verification';
        return view('user.subscription.ensue')->with($view_arr);
    }
}
