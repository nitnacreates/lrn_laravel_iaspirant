<?php

namespace App\Model\Question;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Question extends Model implements Searchable
{
    protected $fillable = ['id'];
    public $timestamps = false;
    protected $dates = ['show_on'];

    public $searchableType = 'Daily Questions';

    public function getSearchResult(): SearchResult
    {
         return new SearchResult(
            $this,
            $this->question,
            route('front::syllabus-questions', ['param' =>  $this->sub, 'topic' => $this->topic_id])
         );
    }
}
