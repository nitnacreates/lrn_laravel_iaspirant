<?php

namespace App\Facades\Helper;
use Illuminate\Support\Carbon;

use App\Model\Prelim\PrelimRoster;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\User;
use App\Model\User\UserSubscription;

// alias is CronHelper
class CronHelper
{
    public function unlockPrelim()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        PrelimRoster::where('locked', 1)
                        ->where('available_from', $today)
                        ->update(['locked' => 0]);
        return true;
    }

    public function authorizeUserSubscription()
    {
        // delete abandoned subscriptions
        $day_before_yester = Carbon::now()->subDays(2)->format('Y-m-d H:i:s');
        UserSubscription::where('payment_verified', 0)
                            ->where('portal_processed', 0)
                            ->where('current_step', UserSubscription::STEP_BEFORE_PAYMENT)
                            ->where('created_at', '<', $day_before_yester)
                            ->delete();

        // verify authorized payments. No deletion for at least 5 days
        // later create a button in admin to verify with button
        $five_days_before = Carbon::now()->subDays(5)->format('Y-m-d H:i:s');
        $authorize_users =  UserSubscription::where('payment_verified', 0)
                            ->where('portal_processed', 1)
                            ->where('current_step', UserSubscription::STEP_AFTER_PAYMENT)
                            ->where('created_at', '<', $five_days_before)
                            ->get();

        if($authorize_users->count()){
            $api = new Api(config('aspirant.razorpay.api_key'), config('aspirant.razorpay.api_secret'));
            foreach ($authorize_users as $auth_user) {
                $order_id = $auth_user->tr_identifier;
                $order = $api->order->fetch($order_id);
                if(is_array($order) && array_key_exists('status', $order) && $order['status'] == 'paid'){
                    $auth_user->current_step = UserSubscription::STEP_PAYMENT_AUTHORIZED;
                    $auth_user->payment_verified = 1;
                    $auth_user->save();
                }
            }
        }

        return true;
    }
}
