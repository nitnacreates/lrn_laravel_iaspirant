@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Prelim Exam</h1>

{{ date_default_timezone_get() }}
<p><strong>Time Left: </strong>{{ $time_remaining }}</p>
@stop
