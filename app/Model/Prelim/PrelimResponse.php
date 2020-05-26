<?php

namespace App\Model\Prelim;

use Illuminate\Database\Eloquent\Model;

class PrelimResponse extends Model
{
    protected $fillable = ['id', 'completed_at'];
    public $timestamps = false;
    protected $dates = ['started_at', 'completed_at'];

    public function user_grab()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function response_questions()
    {
        return $this->hasMany('App\Model\Prelim\PrelimQuestion', 'prelim_roster_id', 'prelim_roster_id');
    }
}
