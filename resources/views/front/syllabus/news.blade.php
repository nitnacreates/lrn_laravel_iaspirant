@extends('front.layouts.skeleton')

@push('col_left_top')
@include('front.partials.syllabus')
@endpush

@section('content')
<h1 class="page-title">Topic News</h1>
@foreach ($news as $cn)
<div class="card mb-3">
  <div class="card-body">
        <h3 class="text-primary"><a href="{{ route('front::news-show', ['param' => $cn->news_link]) }}" >{{ $cn->news_title }}</a></h3>
        <div class="news-area">
        {!! $cn->news_content !!}
        </div>
  </div>
  <div class="card-footer">
    <div class="row">
        <div class="col-6">
            <div class="text-muted">
                <p class="text-sm"><b>Subject: </b>{{ config('aspirant.subject_map')[$cn->sub] }}</p>
                <p class="text-sm"><b>Topic: </b>@include('front.partials.topic_hier', ['topic_arr' => $topic_arr, 'topic_id' => $cn->topic_id])</p>
                <p class="text-sm"><b>Category: </b>@switch($cn->category)
                    @case('b') Both @break
                    @case('p') Prelims @break
                    @case('m') Mains @break
                    @endswitch
                </p>
            </div>     
        </div>
        <div class="col-6">
            <div class="text-muted">
                <p class="text-sm"><b>Created: </b>{{ $cn->created_at->format('d M, Y') }}</p>
                <p class="text-sm"><b>Updated: </b>{{ $cn->updated_at->format('d M, Y') }}</p>
                <p class="text-sm"><b>Weightage: </b>{{ $cn->weightage }}</p>
            </div> 
        </div>
    </div>
  </div>
</div>
@endforeach
@stop
