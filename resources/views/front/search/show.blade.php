@extends('front.layouts.skeleton')

@section('content')
    <h1 class="page-title">Search</h1>
    <div class="row">
        <div class="col">
            <p class="lead">Query: {{ $q }}</p>
            <p class="text-danger">{{ $msg }}</p>
        </div>
        @if(Gate::allows('full-user') && $search_results->count())
        <div class="w-100"></div>
        <div class="col">
            @foreach($search_results->groupByType() as $type => $modelSearchResults)
                <h2>{{ $type }}</h2>
                @foreach($modelSearchResults as $searchResult)
                   <ul>
                        <a href="{{ $searchResult->url }}">{!! $searchResult->title !!}</a>
                   </ul>
                @endforeach
            @endforeach
        </div>
        @endif
    </div>
@stop
