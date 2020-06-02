<?php

namespace App\Model\News;

use Illuminate\Database\Eloquent\Model;

class NewsScore extends Model
{
    protected $fillable = ['id'];
    public $timestamps = false;
    protected $dates = ['news_show_on'];
}
