<?php

namespace App\Model\Essay;

use Illuminate\Database\Eloquent\Model;

class EssayResponse extends Model
{
    protected $fillable = ['id'];

    public function essays()
    {
        return $this->hasMany('App\Model\Essay\Essay');
    }
}
