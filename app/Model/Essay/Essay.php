<?php

namespace App\Model\Essay;

use Illuminate\Database\Eloquent\Model;

class Essay extends Model
{
    protected $fillable = ['id'];
    public $timestamps = false;
    protected $dates = ['created_on'];

    public function essay_response()
    {
        return $this->hasMany('App\Model\Essay\EssayResponse');
    }
}
