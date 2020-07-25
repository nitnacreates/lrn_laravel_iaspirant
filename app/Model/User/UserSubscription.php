<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = ['id'];

    const STEP_BEFORE_PAYMENT = 1;
    const STEP_AFTER_PAYMENT = 2;
    const STEP_PAYMENT_AUTHORIZED = 3;
}
