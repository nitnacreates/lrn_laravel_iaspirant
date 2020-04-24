<?php

namespace App\Model\Theory;

use Illuminate\Database\Eloquent\Model;

class Theory extends Model
{
    protected $fillable = ['id'];

    public function theory_content()
    {
        return $this->hasOne('App\Model\Theory\TheoryContent');
    }

    public function theory_summaries()
    {
        return $this->hasMany('App\Model\Theory\TheorySummary');
    }
}
