<?php

namespace App\Model\News;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{    
    protected $fillable = ['id'];
    protected $dates = ['show_on'];
}
