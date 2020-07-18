@extends('front.layouts.skeleton')
@push('col_left_top')
@include('front.partials.news_read')
@endpush
@section('content')
<h1 class="page-title">News</h1>

<div class="news-wrapper">
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3 class="news-title text-primary"><a href="{{ route('front::news-show', ['param' => $news->news_link]) }}" >{{ $news->news_title }}</a></h3>
                    <div class="news-area bg-white">
                        {!! $news->news_content !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-10">
                            <div class="text-muted">
                                <p class="mb-0"><b>Sub: </b><a href="{{ route('front::syllabus-show', ['param' => $news->sub]) }}">{{ config('aspirant.subject_map')[$news->sub] }}</a><br/>
                                    <b>Topic: </b>@include('front.partials.topic_hier', ['topic_arr' => $topic_arr, 'topic_id' => $news->topic_id, 'sub' => $news->sub])<br/>
                                    <b>Weightage: </b>@include('front.partials.weightage_star', ['weight' => $news->weightage, 'total' => 5])<br/>
                                    <b>Category: </b>@switch($news->category)
                                        @case('b') Prelims & Mains @break
                                        @case('p') Prelims @break
                                        @case('m') Mains @break
                                        @endswitch<br/>
                                </p>
                            </div>
                        </div>
                        <div class="col-2">
                            @if(Gate::allows('pro-user'))
                                <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}" role="button">Create Note</a>
                            @else
                                <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="Pro feature">Create Note</button>
                            @endif
                            <p class="mb-0">&nbsp;</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3>Date References</h3>
    <ul>
    @foreach($news->news_shows as $news_show)
        <li><a href="{{ route('front::news-date', ['param' => $news_show->show_on->format('Y-m-d')]) }}">{{ $news_show->show_on->format('F d, Y') }}</a></li>
    @endforeach
    </ul>
</div>
@stop
