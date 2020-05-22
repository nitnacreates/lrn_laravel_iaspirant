<?php

namespace App\Model\Prelim;

use Illuminate\Database\Eloquent\Model;

class PrelimResponse extends Model
{
    protected $fillable = ['id', 'completed_at'];
    public $timestamps = false;
    protected $dates = ['started_at', 'completed_at'];
}
