@extends('front.layouts.skeleton')

@section('content')
<h3>@ {{ $user_name }}'s Note</h3>

<div class="row">
    <div class="col-6">
        Subject: {{  config('aspirant.subject_map')[$note->sub] }} <br />
        Topic: {{ $note->topic_id }}
    </div>
    <div class="col-6 text-right">
        Last Updated: {{ \FrontHelper::transformDate('Y-m-d H:i:s','M j, Y',$note->updated_at) }} <br /> Created On: {{ \FrontHelper::transformDate('Y-m-d H:i:s','M j, Y',$note->created_at) }}
    </div>
</div>
<div class="row">
    <div class="col-12">
        <h3>{{ $note->note_title }}</h3>
        {!! $note->note_content  !!}
    </div>
    @if($is_user_same)
    <div class="col-12">
        <label>Note for Self</label>
        {!! $note->self_note  !!}
    </div>
    @endif
</div>
@if(Gate::allows('pro-user') && $is_user_same)
<div class="row">
    <div class="col-6">
        Note Status: {{  $note->locked ? 'Hidden' : 'Public' }} <br />
    </div>
    <div class="col-6 text-right">
        <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create', 'id' => $note->id ]) }}" role="button">Edit</a>
    </div>
</div>
@endif

@stop
