@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Prelim Schedule</h1>

@foreach ($rosters as $roster)
    <div class="row border-bottom py-3">
        <div class="col">
            <p><strong>Test: </strong> {{ $roster->title }}</p>
            <h5>Syllabus</h5>
            <p>{{$roster->syllabus}}</p>
            <h5>Resource</h5>
            <p>{{ $roster->resource }}</p>
            <p></p>
            <p><strong>Status: </strong> @if($roster->locked) <span class="text-danger">Locked</span> @else <span class="text-danger">Open</span> @endif</p>
            <p><strong>Available From: </strong> <span class="text-danger">{{ $roster->available_from->format('M d, Y') }}</span> [{{ FrontHelper::daysFromToday($roster->available_from) }} days left]</p>
            @if(!$roster->locked)
                <a href="{{ route('prelim', ['action' => 'prelude', 'id' => $roster->id]) }}" class="btn btn-success">Take Test</a>
                <a href="{{ route('prelim', ['action' => 'report', 'id' => $roster->id]) }}" class="btn btn-warning">Report</a>
            @endif
        </div>
    </div>
@endforeach
@stop
