<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

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
            $amount_to_pay = 100000;
            switch ($params['plan']) {
                case 'premium': 
                    $view_arr['plan'] = 'Premium User';
                    $amount_to_pay = 4999;
                    $view_arr['price'] = $amount_to_pay;
                    break;
                case 'pro': 
                    $view_arr['plan'] = 'Pro User';
                    $amount_to_pay = 9999;
                    $view_arr['price'] = $amount_to_pay;
                    break;
                default:
                    $this->request->session()->flash('msgs', ['Subscription does not exist']);
                    return redirect()->route('user::subscription', ['action' => 'show']);
                break;
            }
        }

        // view arr
        $user_id = \UserHelper::getUserId();
        $view_arr['user'] = User::with('user_keep')->find($user_id);

        // razorpay
        $api = new Api(config('aspirant.razorpay.api_key'), config('aspirant.razorpay.api_secret'));
        $razor_amount_topay = $amount_to_pay*100;

        $order  = $api->order->create([
            'receipt' => '123', 
            'amount' => $razor_amount_topay, 
            'currency' => 'INR'
        ]);
        $razorpayOrderId = $order['id'];

        // $razorpayOrderId = 99; 

        $view_arr['razordata'] = [
            "key"               => config('aspirant.razorpay.api_key'),
            "amount"            => $razor_amount_topay,
            "name"              => "DJ Tiesto",
            "description"       => "Tron Legacy",
            "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
            "prefill"           => [
                "name"              => "Daft Punk",
                "email"             => "customer@merchant.com",
                "contact"           => "9999999999",
            ],
            "notes"             => [
                "address"           => "Hello World",
                "merchant_order_id" => "12312321",
            ],
            "theme"             => [
                "color"             => "#F37254"
            ],
            "order_id"          => $razorpayOrderId,
        ];

        $view_arr['pagetitle'] = 'Subscription Review';
        $view_arr['razorpay_order_id'] = $razorpayOrderId;
        return view('user.subscription.review')->with($view_arr);
    }

    public function ensue()
    {
        $params = $this->request->all();
        $api = new Api(config('aspirant.razorpay.api_key'), config('aspirant.razorpay.api_secret'));

        $verify = [
            'razorpay_order_id' => $params['razorpay_order_id'],
            'razorpay_payment_id' => $params['razorpay_payment_id'],
            'razorpay_signature' => $params['razorpay_signature']
        ];

        $success = true;
        $msg = "Your payment was successfull";
        try{
            $result = $api->utility->verifyPaymentSignature($verify);
            // dump($result);
        } catch(SignatureVerificationError $e){
            $success = false;
            $msg = $e->getMessage();
        }

        $view_arr['success'] = $success;
        $view_arr['msg'] = $msg;
        $view_arr['pagetitle'] = 'Subscription Verification';
        return view('user.subscription.ensue')->with($view_arr);
    }
}
