@extends('front.layouts.skeleton')

@section('content')
    <h1 class="page-title">Search</h1>
    <div class="row">
        <div class="col">
            <p class="lead">Query: {{ $q }}</p>
            <p class="text-danger">{{ $msg }}</p>
        </div>
    </div>
@stop
