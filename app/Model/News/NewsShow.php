<?php

namespace App\Model\News;

use Illuminate\Database\Eloquent\Model;

class NewsShow extends Model
{
    protected $fillable = ['id', 'locked'];
    public $timestamps = false;
    protected $dates = ['show_on'];

    public function news()
    {
        return $this->belongsToMany('App\Model\News\News');
    }
}
