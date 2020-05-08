@extends('front.layouts.skeleton')

@section('content')
<h3>User notes</h3>
<div class="row">
    <div class="col-12">
        @foreach($notes as $note)
            {{ $note->note_title }}
        @endforeach
    </div>
    @if(Gate::allows('full-user') && $is_user_same)
    <div class="col-12 text-right">
        <a class="btn btn-primary" href="{{ route('front::notes', ['user_name' => auth()->user()->name, 'controller' => 'notes', 'action' => 'create']) }}" role="button">New Note</a>
    </div>
    @endif
</div>

@stop
