<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;

use App\Model\News\News;
use App\Model\Question\Question;


class SearchController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'search');
        view()->share('pagetitle', 'Search');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        $query = $this->request->input('q','');
        $query = preg_replace("/[^a-zA-Z0-9\s]/", "", $query);
        
        if(\Gate::any(['penman-user', 'pro-user'])){
            if(!empty($query) && (strlen($query) > 2)){
            
                $searchResults = (new Search())
                                    ->registerModel(News::class, function(ModelSearchAspect $modelSearchAspect) {
                                        $modelSearchAspect
                                            ->addSearchableAttribute('news_title')
                                            ->addSearchableAttribute('news_content')
                                            ->where('locked', 0);
                                    })
                                    ->registerModel(Question::class, function(ModelSearchAspect $modelSearchAspect) {
                                        $modelSearchAspect
                                            ->addSearchableAttribute('question')
                                            ->addSearchableAttribute('explanation')
                                            ->where('type', 1)
                                            ->where('locked', 0);
                                    })
                                    ->perform($query);

                $view_arr['search_results'] = $searchResults;
                $msg = "Total {$searchResults->count()} results found";
            } else{
                $msg = "search needs to be at least 3 characters";
            }

        } else{
            $msg = "Searching is available for " . config('aspirant.user_subscription')['15'] . " and ". config('aspirant.user_subscription')['20'] ." users";
        }

        $view_arr['q'] = $query;
        $view_arr['msg'] = $msg;

        return view('front.search.show')->with($view_arr);
    }
}
