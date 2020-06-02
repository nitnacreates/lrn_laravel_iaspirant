<?php

namespace App\Model\News;

use Illuminate\Database\Eloquent\Model;

class NewsScore extends Model
{
    public $timestamps = false;
    protected $dates = ['news_show_on'];
}
