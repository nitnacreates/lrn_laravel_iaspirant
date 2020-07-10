@extends('front.layouts.skeleton')
@push('col_left_top')
@include('front.partials.news_read')
@endpush
@section('content')
<h1 class="page-title">News</h1>

<div class="row mt-4 {{ $news->type == 'dn' ? 'news-brick' : 'news-editorial' }}">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h3 class="text-primary"><a href="{{ route('front::news-show', ['param' => $news->news_link]) }}" >{{ $news->news_title }}</a></h3>
                <div class="news-area">
                    {!! $news->news_content !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <div class="text-muted">
                    <p class="text-sm"><b>Sub: </b>{{ config('aspirant.subject_map')[$news->sub] }}</p>
                    <p class="text-sm"><b>Topic: </b>{{ $news->topic_id }}</p>
                    <p class="text-sm"><b>Rating: </b>@include('front.partials.weightage_star', ['weight' => $news->weightage, 'total' => 5])</p>
                    <p class="text-sm"><b>Category: </b>@switch($news->category)
                        @case('b') Prelims+Mains @break
                        @case('p') Prelims @break
                        @case('m') Mains @break
                        @endswitch
                    </p>
                </div>
            </div>
            <div class="col-2">
                @if(Gate::allows('full-user'))
                    <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}" role="button">Create Note</a>
                @else
                    <button type="button" class="btn btn-default" data-toggle="tooltip" title="Subscribe">Create Note</button>
                @endif
                
            </div>
        </div>
    </div>
</div>
@stop
