@extends('front.layouts.skeleton')

@push('col_left_top')
@include('front.partials.syllabus')
@endpush

@section('content')
<h1 class="page-title">{{ $pagetitle }}</h1>
<div class="news-wrapper">
    @foreach ($news as $cn)
    <div class="card mb-3 {{ $cn->type == 'dn' ? 'news-brick' : 'news-editorial' }}"">
      <div class="card-header">
        <div class="row">
            <div class="col-9">
                <div class="text-muted">
                    <p><b>Sub: </b><a href="{{ route('front::syllabus-show', ['param' => $cn->sub]) }}">{{ config('aspirant.subject_map')[$cn->sub] }}</a><br/>
                        <b>Topic: </b>@include('front.partials.topic_hier', ['topic_arr' => $topic_arr, 'topic_id' => $cn->topic_id, 'sub' => $cn->sub])<br/>
                        <b>Weightage: </b>@include('front.partials.weightage_star', ['weight' => $cn->weightage, 'total' => 5])<br/>
                        <b>Category: </b>@switch($cn->category)
                            @case('b') Prelims & Mains @break
                            @case('p') Prelims @break
                            @case('m') Mains @break
                            @endswitch<br/>
                    </p>
                </div>
            </div>
            <div class="col-3">
                @if(Gate::allows('pro-user'))
                    <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}" role="button">Create Note</a>
                @else
                    <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="Pro feature">Create Note</button>
                @endif
                <div class="text-muted">
                    <p><b>Created: </b>{{ $cn->created_at->format('d M, Y') }}<br />
                        <b>Updated: </b>{{ $cn->updated_at->format('d M, Y') }}
                    </p>
                </div>
            </div>
        </div>
      </div>
      <div class="card-body">
            <h3 class="text-primary"><a href="{{ route('front::news-show', ['param' => $cn->news_link]) }}" >{{ $cn->news_title }}</a></h3>
            <div class="news-area">
            {!! $cn->news_content !!}
            </div>
      </div>
    </div>
    @endforeach
</div>
<div class="pagination_section float-right">
    {{ $news->appends(['topic' => $topic_id])->links() }}
</div>
@stop
