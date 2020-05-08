@extends('front.layouts.skeleton')

@section('content')
<h3>User notes</h3>
<div class="row">
    <div class="col-12">
        @foreach($notes as $note)
            {{ $note->note_title }}
        @endforeach
    </div>
</div>

@stop
