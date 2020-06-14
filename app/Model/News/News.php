<?php

namespace App\Model\News;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class News extends Model implements Searchable
{    
    protected $fillable = ['id'];
    public $searchableType = 'News';

    public function news_shows()
    {
        return $this->belongsToMany('App\Model\News\NewsShow');
    }

    public function getSearchResult(): SearchResult
    {
     
         return new SearchResult(
            $this,
            $this->news_title,
            route('front::news-date', ['param' => $this->show_on->format('Y-m-d')])
         );
    }
}
