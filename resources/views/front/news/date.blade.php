@extends('front.layouts.skeleton')

@section('content')
<h2>News</h2>

@foreach ($news as $cn)
<div class="row mt-4 border-bottom {{ $cn->type == 'dn' ? 'news-brick' : 'news-editorial' }}">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h3 class="text-primary"><a href="{{ route('front::news-show', ['param' => $cn->news_link]) }}" >{{ $cn->news_title }}</a></h3>
                <div class="news-area">
                    {!! $cn->news_content !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <div class="text-muted">
                    <p class="text-sm"><b class="d-block">Sub</b>{{ config('aspirant.subject_map')[$cn->sub] }}</p>
                    <p class="text-sm"><b class="d-block">Topic</b>{{ $cn->topic_id }}</p>
                    <p class="text-sm"><b class="d-block">Rating</b>{{ $cn->weightage }}</p>
                    <p class="text-sm"><b class="d-block">Category</b>@switch($cn->category)
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
                    <button type="button" class="btn btn-default">Create Note</button>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endforeach

<h2>Questions</h2>

@include('front.partials.comments', $page_comments)
@stop
