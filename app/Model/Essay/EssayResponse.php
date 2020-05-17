<?php

namespace App\Model\Essay;

use Illuminate\Database\Eloquent\Model;

class EssayResponse extends Model
{
    protected $fillable = ['id'];

    public function essays()
    {
        return $this->belongsTo('App\Model\Essay\Essay');
    }

    public function essay_reactions()
    {
        return $this->hasMany('App\Model\Essay\EssayReaction');
    }

    public function grouped_essay_reactions()
    {
        return $this->hasMany('App\Model\Essay\EssayReaction');
    }
}
