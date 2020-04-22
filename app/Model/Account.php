<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;
    protected $guarded = ['all_id','user_type'];
}
